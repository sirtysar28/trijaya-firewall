<?php
/**
 * One-click demo setup — import dari file JSON (inc/demo-content.json)
 * + dukungan import WXR (inc/demo-content.wxr.xml) via WordPress Importer.
 *
 * Alur:
 * - Cara A: Appearance → Trijaya Setup → "Import Halaman & Menu Sekarang"
 *   (import penuh: halaman + kategori + menu, anti-timeout).
 * - Cara B: Tools → Import → WordPress dengan demo-content.wxr.xml
 *   (halaman + kategori). Setelah import, theme OTOMATIS membangun
 *   Menu Utama & Menu Footer + men-set homepage (lihat trijaya_setup_autofix_locations).
 *
 * @package Trijaya_Firewall
 */

defined( 'ABSPATH' ) || exit;

/**
 * Path file demo-content.json (bisa dioverride via filter).
 */
function trijaya_demo_content_path(): string {
	return apply_filters( 'trijaya_demo_content_path', TRIJAYA_DIR . '/inc/demo-content.json' );
}

/**
 * Path file WXR untuk WordPress Importer (Tools → Import → WordPress).
 */
function trijaya_demo_wxr_path(): string {
	return apply_filters( 'trijaya_demo_wxr_path', TRIJAYA_DIR . '/inc/demo-content.wxr.xml' );
}

/**
 * Muat & validasi demo-content.json.
 *
 * @return array Data demo content.
 */
function trijaya_load_demo_content(): array {
	$path = trijaya_demo_content_path();

	if ( ! is_readable( $path ) ) {
		wp_die( esc_html__( 'File demo-content.json tidak ditemukan di folder theme.', 'trijaya-firewall' ) );
	}

	$data = json_decode( (string) file_get_contents( $path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions

	if ( ! is_array( $data ) || empty( $data['pages'] ) || empty( $data['menus'] ) ) {
		wp_die( esc_html__( 'Format demo-content.json tidak valid.', 'trijaya-firewall' ) );
	}

	return $data;
}

/* -------------------------------------------------------------------------
 * Performa (dipakai import besar: 123 halaman + 142 item menu)
 * ---------------------------------------------------------------------- */

/**
 * Mode turbo import: WP_IMPORTING + time limit + memory + deferred counting.
 */
function trijaya_import_turbo_on(): void {
	if ( ! defined( 'WP_IMPORTING' ) ) {
		define( 'WP_IMPORTING', true );
	}
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 600 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}
	if ( function_exists( 'wp_raise_memory_limit' ) ) {
		wp_raise_memory_limit( 'admin' );
	}
	if ( function_exists( 'wp_defer_term_counting' ) ) {
		wp_defer_term_counting( true );
	}
	if ( function_exists( 'wp_defer_comment_counting' ) ) {
		wp_defer_comment_counting( true );
	}
}

/**
 * Matikan mode turbo (kembalikan counting).
 */
function trijaya_import_turbo_off(): void {
	if ( function_exists( 'wp_defer_term_counting' ) ) {
		wp_defer_term_counting( false );
	}
	if ( function_exists( 'wp_defer_comment_counting' ) ) {
		wp_defer_comment_counting( false );
	}
}

/**
 * Map slug-path halaman => ID dalam SATU query
 * (jauh lebih ringan daripada get_page_by_path() per halaman).
 *
 * @return array<string, int>
 */
function trijaya_page_path_map(): array {
	$pages = get_pages(
		array(
			'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'number'      => 0,
		)
	);
	if ( ! $pages ) {
		return array();
	}

	$names     = array();
	$by_parent = array();
	foreach ( $pages as $p ) {
		$names[ (int) $p->ID ]                = $p->post_name;
		$by_parent[ (int) $p->post_parent ][] = (int) $p->ID;
	}

	$map  = array();
	$walk = function ( int $id, string $prefix ) use ( &$map, &$walk, $names, $by_parent ): void {
		$full       = $prefix . $names[ $id ];
		$map[ $full ] = $id;
		foreach ( ( $by_parent[ $id ] ?? array() ) as $child ) {
			$walk( $child, $full . '/' );
		}
	};
	foreach ( ( $by_parent[0] ?? array() ) as $root_id ) {
		$walk( $root_id, '' );
	}

	return $map;
}

/* -------------------------------------------------------------------------
 * Builder: kategori & menu (dipakai Cara A dan auto-build setelah WXR)
 * ---------------------------------------------------------------------- */

/**
 * Pastikan semua kategori dari JSON ada; kembalikan map slug => term ID.
 *
 * @param array $categories Daftar kategori dari demo-content.json.
 * @return array<string, int>
 */
function trijaya_setup_ensure_categories( array $categories ): array {
	$terms = array();

	foreach ( $categories as $cat ) {
		$slug = isset( $cat['slug'] ) ? (string) $cat['slug'] : '';
		if ( '' === $slug ) {
			continue;
		}
		$title    = isset( $cat['title'] ) ? (string) $cat['title'] : $slug;
		$existing = get_term_by( 'slug', $slug, 'category' );

		if ( $existing ) {
			$terms[ $slug ] = (int) $existing->term_id;
			continue;
		}

		$result = wp_insert_term(
			$title,
			'category',
			array(
				'slug'        => $slug,
				'description' => isset( $cat['description'] ) ? (string) $cat['description'] : '',
			)
		);
		if ( ! is_wp_error( $result ) ) {
			$terms[ $slug ] = (int) $result['term_id'];
		}
	}

	return $terms;
}

/**
 * Kosongkan isi menu (dipakai sebelum rebuild agar tidak dobel).
 */
function trijaya_setup_clear_menu( int $menu_id ): void {
	foreach ( wp_get_nav_menu_items( $menu_id ) as $item ) {
		wp_delete_post( $item->ID, true );
	}
}

/**
 * Ambil ID menu berdasarkan nama; buat baru bila belum ada.
 */
function trijaya_setup_get_menu_id( string $name ): int {
	$menu = wp_get_nav_menu_object( $name );
	if ( $menu ) {
		return (int) $menu->term_id;
	}
	return (int) wp_create_menu( $name );
}

/**
 * Tambahkan item menu (rekursif) dari struktur JSON.
 *
 * @param int   $menu_id     ID menu (term).
 * @param array $items       Item menu dari JSON (title, type, path|slug|url, children, classes).
 * @param int   $parent_item ID item menu parent.
 * @param array $ids         Map path halaman => page ID (kunci 'home' untuk homepage).
 * @param array $terms       Map slug kategori => term ID.
 * @param int   $position    Nomor urut (by reference).
 */
function trijaya_setup_add_json_menu_items( int $menu_id, array $items, int $parent_item, array $ids, array $terms, int &$position ): void {
	foreach ( $items as $item ) {
		$type  = isset( $item['type'] ) ? (string) $item['type'] : 'page';
		$title = isset( $item['title'] ) ? (string) $item['title'] : '';

		$args = array(
			'menu-item-title'     => $title,
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $position++,
			'menu-item-parent-id' => $parent_item,
		);
		if ( ! empty( $item['classes'] ) ) {
			$args['menu-item-classes'] = (string) $item['classes'];
		}

		if ( 'category' === $type ) {
			$slug = isset( $item['slug'] ) ? (string) $item['slug'] : '';
			if ( '' === $slug || empty( $terms[ $slug ] ) ) {
				continue;
			}
			$args['menu-item-type']      = 'taxonomy';
			$args['menu-item-object']    = 'category';
			$args['menu-item-object-id'] = (int) $terms[ $slug ];
		} elseif ( 'custom' === $type ) {
			$args['menu-item-type'] = 'custom';
			$url                     = isset( $item['url'] ) ? (string) $item['url'] : '';
			if ( 'whatsapp' === ( isset( $item['slug'] ) ? $item['slug'] : '' ) && function_exists( 'trijaya_whatsapp_link' ) ) {
				$url = trijaya_whatsapp_link();
			}
			$args['menu-item-url'] = $url ? $url : home_url( '/' );
		} else {
			/* type: page | home */
			$path      = isset( $item['path'] ) ? (string) $item['path'] : '';
			$lookup    = ( 'home' === $type || 'home' === $path ) ? 'home' : $path;
			$object_id = isset( $ids[ $lookup ] ) ? (int) $ids[ $lookup ] : 0;
			if ( ! $object_id ) {
				continue;
			}
			$args['menu-item-type']      = 'post_type';
			$args['menu-item-object']    = 'page';
			$args['menu-item-object-id'] = $object_id;
		}

		$item_id = wp_update_nav_menu_item( $menu_id, 0, $args );

		if ( $item_id && ! is_wp_error( $item_id ) && ! empty( $item['children'] ) ) {
			trijaya_setup_add_json_menu_items( $menu_id, (array) $item['children'], (int) $item_id, $ids, $terms, $position );
		}
	}
}

/**
 * Bangun Menu Utama + Menu Footer dari data JSON.
 *
 * @param array $menus_data Bagian "menus" dari demo-content.json.
 * @param array $ids        Map path halaman => page ID.
 * @param array $terms      Map slug kategori => term ID.
 * @return array Lokasi menu yang berhasil dibuat (location => menu_id).
 */
function trijaya_setup_build_menus( array $menus_data, array $ids, array $terms ): array {
	$locations = array();

	foreach ( array( 'primary', 'footer' ) as $location ) {
		$menu_data = isset( $menus_data[ $location ] ) ? $menus_data[ $location ] : null;
		if ( ! $menu_data || empty( $menu_data['items'] ) ) {
			continue;
		}

		$name    = ! empty( $menu_data['name'] ) ? (string) $menu_data['name'] : 'Menu ' . ucfirst( $location );
		$menu_id = trijaya_setup_get_menu_id( $name );
		trijaya_setup_clear_menu( $menu_id );

		$position = 1;
		trijaya_setup_add_json_menu_items( $menu_id, (array) $menu_data['items'], 0, $ids, $terms, $position );

		$locations[ $location ] = $menu_id;
	}

	return $locations;
}

/* -------------------------------------------------------------------------
 * Cara A — import penuh via theme (JSON)
 * ---------------------------------------------------------------------- */

/**
 * Proses import demo content dari JSON (via admin-post).
 * Anti-timeout: WP_IMPORTING + set_time_limit + memory + deferred counting
 * + lookup halaman 1 query. Aman dijalankan ulang (melanjutkan yang tersisa).
 */
function trijaya_run_demo_setup(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Akses ditolak.', 'trijaya-firewall' ) );
	}
	check_admin_referer( 'trijaya_demo_setup' );

	trijaya_import_turbo_on();

	$data = trijaya_load_demo_content();
	$ids  = array();

	/* ---- 1. Halaman Home (front page) ---- */
	$home_id = (int) get_option( 'page_on_front' );
	if ( ! $home_id ) {
		$home_id = (int) wp_insert_post(
			array(
				'post_title'   => isset( $data['home']['title'] ) ? $data['home']['title'] : 'Home',
				'post_name'    => isset( $data['home']['slug'] ) ? $data['home']['slug'] : 'home',
				'post_content' => '',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			)
		);
	}
	$ids['home'] = $home_id;

	/* ---- 2. Halaman blog (Insights) ---- */
	$blog_slug = isset( $data['blog']['slug'] ) ? (string) $data['blog']['slug'] : 'insights';
	$existing  = trijaya_page_path_map();
	$blog_id   = ! empty( $existing[ $blog_slug ] ) ? (int) $existing[ $blog_slug ] : 0;

	if ( ! $blog_id ) {
		$blog_id = (int) wp_insert_post(
			array(
				'post_title'  => isset( $data['blog']['title'] ) ? $data['blog']['title'] : 'Insights',
				'post_name'   => $blog_slug,
				'post_status' => 'publish',
				'post_type'   => 'page',
			)
		);
		$existing[ $blog_slug ] = $blog_id;
	}
	if ( $blog_id ) {
		$ids[ $blog_slug ] = $blog_id;
	}

	/* ---- 3. Semua halaman dari JSON (dengan konten) ---- */
	$created = 0;
	$skipped = 0;

	foreach ( (array) $data['pages'] as $page ) {
		$path = isset( $page['path'] ) ? (string) $page['path'] : '';

		if ( '' === $path ) {
			continue;
		}

		/* Sudah ada? pakai yang ada (aman diulang, tidak menimpa konten user). */
		if ( ! empty( $existing[ $path ] ) ) {
			$ids[ $path ] = (int) $existing[ $path ];
			$skipped++;
			continue;
		}

		$parent_id = 0;
		if ( ! empty( $page['parent'] ) ) {
			$ref = (string) $page['parent'];
			if ( isset( $ids[ $ref ] ) ) {
				$parent_id = (int) $ids[ $ref ];
			} elseif ( ! empty( $existing[ $ref ] ) ) {
				$parent_id = (int) $existing[ $ref ];
			}
		}

		$id = wp_insert_post(
			array(
				'post_title'   => isset( $page['title'] ) ? (string) $page['title'] : $path,
				'post_name'    => isset( $page['slug'] ) ? (string) $page['slug'] : '',
				'post_content' => isset( $page['content'] ) ? (string) $page['content'] : '',
				'post_status'  => isset( $page['status'] ) ? (string) $page['status'] : 'publish',
				'post_type'    => 'page',
				'post_parent'  => $parent_id,
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$ids[ $path ] = (int) $id;
			$created++;
		}
	}

	/* ---- 4. Kategori blog (submenu Insights) ---- */
	$terms = trijaya_setup_ensure_categories( (array) ( isset( $data['categories'] ) ? $data['categories'] : array() ) );

	/* ---- 5. Set front page statis + halaman blog ---- */
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_id );
	update_option( 'page_for_posts', $blog_id );
	flush_rewrite_rules();

	/* ---- 6. Bangun menu dari JSON ---- */
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations = array_merge( $locations, trijaya_setup_build_menus( (array) $data['menus'], $ids, $terms ) );
	set_theme_mod( 'nav_menu_locations', $locations );

	trijaya_import_turbo_off();

	set_transient(
		'trijaya_demo_done',
		array(
			'created' => $created,
			'skipped' => $skipped,
			'terms'   => count( $terms ),
		),
		120
	);
	wp_safe_redirect( admin_url( 'themes.php?page=trijaya-setup&done=1' ) );
	exit;
}
add_action( 'admin_post_trijaya_demo_setup', 'trijaya_run_demo_setup' );

/* -------------------------------------------------------------------------
 * Cara B — auto-fix setelah import WXR via WordPress Importer
 * ---------------------------------------------------------------------- */

/**
 * Auto-fix (admin_init):
 * - Assign / bangun "Menu Utama" & "Menu Footer" bila lokasinya kosong.
 * - Set homepage statis (Home) & halaman blog (Insights) bila belum di-set.
 * Hanya mengisi yang KOSONG — tidak pernah menimpa pilihan user.
 */
function trijaya_setup_autofix_locations(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$menu      = wp_get_nav_menu_object( 'Menu Utama' );

	if ( empty( $locations['primary'] ) ) {
		if ( $menu && count( wp_get_nav_menu_items( $menu->term_id ) ) > 0 ) {
			/* Menu sudah ada (mis. hasil import WXR lama) → assign saja. */
			$locations['primary'] = (int) $menu->term_id;
		} elseif ( trijaya_setup_maybe_build_menus() ) {
			$locations = get_theme_mod( 'nav_menu_locations', array() );
		}
	}

	if ( empty( $locations['footer'] ) ) {
		$footer = wp_get_nav_menu_object( 'Menu Footer' );
		if ( $footer ) {
			$locations['footer'] = (int) $footer->term_id;
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );

	if ( ! get_option( 'page_on_front' ) ) {
		$home = get_page_by_path( 'home' );
		if ( $home instanceof WP_Post ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home->ID );
		}
	}

	if ( ! get_option( 'page_for_posts' ) ) {
		$blog = get_page_by_path( 'insights' );
		if ( $blog instanceof WP_Post ) {
			update_option( 'page_for_posts', $blog->ID );
		}
	}
}
add_action( 'admin_init', 'trijaya_setup_autofix_locations', 99 );

/**
 * Bangun Menu Utama & Menu Footer otomatis dari demo-content.json
 * (dipakai setelah halaman diimport via WordPress Importer / Cara B).
 *
 * @return bool True bila menu berhasil dibangun.
 */
function trijaya_setup_maybe_build_menus(): bool {
	/* Halaman demo harus sudah ada — cek penanda via path map (1 query). */
	$map = trijaya_page_path_map();
	if ( empty( $map['solutions'] ) && empty( $map['about-us'] ) ) {
		return false;
	}

	if ( ! is_readable( trijaya_demo_content_path() ) ) {
		return false;
	}

	$data = json_decode( (string) file_get_contents( trijaya_demo_content_path() ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! is_array( $data ) || empty( $data['menus'] ) ) {
		return false;
	}

	trijaya_import_turbo_on();

	$ids = $map;

	$home = get_page_by_path( 'home' );
	if ( $home instanceof WP_Post ) {
		$ids['home'] = (int) $home->ID;
	}

	$blog_slug = isset( $data['blog']['slug'] ) ? (string) $data['blog']['slug'] : 'insights';
	if ( empty( $ids[ $blog_slug ] ) ) {
		$blog = get_page_by_path( $blog_slug );
		if ( $blog instanceof WP_Post ) {
			$ids[ $blog_slug ] = (int) $blog->ID;
		}
	}

	$terms     = trijaya_setup_ensure_categories( (array) ( isset( $data['categories'] ) ? $data['categories'] : array() ) );
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations = array_merge( $locations, trijaya_setup_build_menus( (array) $data['menus'], $ids, $terms ) );
	set_theme_mod( 'nav_menu_locations', $locations );

	trijaya_import_turbo_off();

	return ! empty( $locations['primary'] );
}

/* -------------------------------------------------------------------------
 * Download file demo content
 * ---------------------------------------------------------------------- */

/**
 * Download demo-content.wxr.xml untuk WordPress Importer
 * (Tools → Import → WordPress).
 */
function trijaya_download_demo_wxr(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Akses ditolak.', 'trijaya-firewall' ) );
	}
	check_admin_referer( 'trijaya_demo_wxr' );

	$path = trijaya_demo_wxr_path();
	if ( ! is_readable( $path ) ) {
		wp_die( esc_html__( 'File demo-content.wxr.xml tidak ditemukan.', 'trijaya-firewall' ) );
	}

	nocache_headers();
	header( 'Content-Type: application/xml; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="trijaya-demo-content.xml"' );
	header( 'Content-Length: ' . (string) filesize( $path ) );
	readfile( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	exit;
}
add_action( 'admin_post_trijaya_download_demo_wxr', 'trijaya_download_demo_wxr' );

/**
 * Download demo-content.json dari halaman Trijaya Setup
 * (untuk backup / import manual di instalasi lain).
 */
function trijaya_download_demo_json(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Akses ditolak.', 'trijaya-firewall' ) );
	}
	check_admin_referer( 'trijaya_demo_json' );

	$path = trijaya_demo_content_path();
	if ( ! is_readable( $path ) ) {
		wp_die( esc_html__( 'File demo-content.json tidak ditemukan.', 'trijaya-firewall' ) );
	}

	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="trijaya-demo-content.json"' );
	header( 'Content-Length: ' . (string) filesize( $path ) );
	readfile( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	exit;
}
add_action( 'admin_post_trijaya_download_demo_json', 'trijaya_download_demo_json' );

/* -------------------------------------------------------------------------
 * Halaman admin: Trijaya Setup
 * ---------------------------------------------------------------------- */

/**
 * Halaman admin: Trijaya Setup.
 */
function trijaya_setup_admin_page(): void {
	add_theme_page(
		__( 'Trijaya Setup', 'trijaya-firewall' ),
		__( 'Trijaya Setup', 'trijaya-firewall' ),
		'manage_options',
		'trijaya-setup',
		'trijaya_setup_admin_render'
	);
}
add_action( 'admin_menu', 'trijaya_setup_admin_page' );

/**
 * Render halaman admin Trijaya Setup.
 */
function trijaya_setup_admin_render(): void {
	$done = get_transient( 'trijaya_demo_done' );
	$json = is_readable( trijaya_demo_content_path() ) ? trijaya_load_demo_content() : null;
	$wxr  = is_readable( trijaya_demo_wxr_path() );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( '⚡ Trijaya Firewall — Setup Cepat', 'trijaya-firewall' ); ?></h1>
		<p><?php esc_html_e( 'Theme v1.4.2 — dibuat oleh Tim Trijaya Solution.', 'trijaya-firewall' ); ?>
		<a href="https://trijayasolution.com" target="_blank" rel="noopener">trijayasolution.com</a></p>

		<?php if ( $done && is_array( $done ) ) : ?>
			<div class="notice notice-success">
				<p>
					<?php
					printf(
						/* translators: 1: jumlah halaman baru, 2: jumlah halaman sudah ada, 3: jumlah kategori. */
						esc_html__( 'Import berhasil! %1$d halaman baru dibuat, %2$d halaman sudah ada (dipakai ulang), %3$d kategori blog. Menu Utama (3 level) & Menu Footer tersusun otomatis. Homepage sudah di-set. 🎉', 'trijaya-firewall' ),
						(int) $done['created'],
						(int) $done['skipped'],
						(int) $done['terms']
					);
					?>
				</p>
			</div>
		<?php elseif ( $done ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Import berhasil! 🎉', 'trijaya-firewall' ); ?></p></div>
		<?php endif; ?>

		<div class="card" style="max-width:760px;padding:8px 20px 20px;">
			<h2><?php esc_html_e( 'Cara A — Import via Theme (disarankan, tanpa plugin)', 'trijaya-firewall' ); ?></h2>
			<?php if ( $json ) : ?>
				<p>
					<?php
					printf(
						/* translators: 1: jumlah halaman, 2: jumlah kategori. */
						esc_html__( 'Sumber: inc/demo-content.json — %1$d halaman (konten SEO siap pakai) + %2$d kategori blog + Menu Utama 3 level (Home, Solutions, Services, Products, Industries, Projects, Insights, About Us, Contact) + Menu Footer.', 'trijaya-firewall' ),
						count( (array) $json['pages'] ),
						count( (array) $json['categories'] )
					);
					?>
				</p>
			<?php endif; ?>
			<p><?php esc_html_e( 'Import aman diulang (melanjutkan yang tersisa bila pernah timeout), halaman yang sudah ada tidak ditimpa, menu disusun ulang otomatis.', 'trijaya-firewall' ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="trijaya_demo_setup">
				<?php wp_nonce_field( 'trijaya_demo_setup' ); ?>
				<?php submit_button( __( '🚀 Import Halaman & Menu Sekarang', 'trijaya-firewall' ), 'primary large', 'submit', false ); ?>
			</form>

			<hr>
			<h2><?php esc_html_e( 'Cara B — Import via WordPress Importer (Tools → Import)', 'trijaya-firewall' ); ?></h2>
			<ol style="list-style:decimal;padding-left:20px;">
				<li><?php esc_html_e( 'Klik "Download XML" di bawah, lalu buka Tools → Import → WordPress → Install Now / Run Importer.', 'trijaya-firewall' ); ?></li>
				<li><?php esc_html_e( 'Upload file trijaya-demo-content.xml → klik "Upload file and import" → pilih author → Submit.', 'trijaya-firewall' ); ?></li>
				<li><?php esc_html_e( 'Setelah import selesai, buka halaman admin mana pun (mis. Pages) — theme OTOMATIS membuat Menu Utama & Menu Footer + men-set homepage dan halaman blog Insights.', 'trijaya-firewall' ); ?></li>
			</ol>
			<p><em>
				<?php esc_html_e( 'Catatan: file XML berisi halaman + kategori saja (format WXR standar, ringan & cepat). Menu tidak perlu diimport — theme yang membangunnya otomatis. Disarankan nonaktifkan sementara plugin berat lain saat import.', 'trijaya-firewall' ); ?>
				<?php if ( $wxr ) : ?>
					<br><?php esc_html_e( 'PENTING: WordPress Importer hanya menerima file .XML — jangan upload file .json ke Tools → Import.', 'trijaya-firewall' ); ?>
				<?php endif; ?>
			</em></p>
			<?php if ( $wxr ) : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:8px;">
					<input type="hidden" name="action" value="trijaya_download_demo_wxr">
					<?php wp_nonce_field( 'trijaya_demo_wxr' ); ?>
					<button type="submit" class="button button-secondary"><?php esc_html_e( '⬇️ Download XML (untuk WordPress Importer)', 'trijaya-firewall' ); ?></button>
				</form>
			<?php endif; ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:8px;">
				<input type="hidden" name="action" value="trijaya_download_demo_json">
				<?php wp_nonce_field( 'trijaya_demo_json' ); ?>
				<button type="submit" class="button"><?php esc_html_e( '⬇️ Download JSON (backup / tool lain)', 'trijaya-firewall' ); ?></button>
			</form>

			<hr>
			<h2><?php esc_html_e( 'Kustomisasi', 'trijaya-firewall' ); ?></h2>
			<ul style="list-style:disc;padding-left:20px;">
				<li><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Appearance → Menus — edit Menu Utama & Menu Footer', 'trijaya-firewall' ); ?></a></li>
				<li><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customizer — Logo, Hero/Slider, Warna, Kontak, AI Chat', 'trijaya-firewall' ); ?></a></li>
				<li><a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=trijaya_hero' ) ); ?>"><?php esc_html_e( 'Hero Slider — isi lebih dari 1 slide untuk mode slider', 'trijaya-firewall' ); ?></a></li>
				<li><a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=trijaya_chat' ) ); ?>"><?php esc_html_e( 'AI Chat (Groq) — tempel API Key', 'trijaya-firewall' ); ?></a></li>
			</ul>
			<p><em><?php esc_html_e( 'Catatan: menu lengkap sudah otomatis tampil di header walau import belum dijalankan (fallback bawaan theme dari inc/menus.php).', 'trijaya-firewall' ); ?></em></p>

			<hr>
			<h2><?php esc_html_e( 'Edit Section dengan Elementor', 'trijaya-firewall' ); ?></h2>
			<p><?php esc_html_e( 'Setiap section homepage bisa diganti dengan Elementor: buat halaman dengan slug section (mis. layanan, solutions, faq, kontak) lalu edit dengan Elementor — otomatis menggantikan section bawaan. Bisa juga pakai shortcode [trijaya_section slug="hero"] di halaman mana pun.', 'trijaya-firewall' ); ?></p>
		</div>
	</div>
	<?php
}
