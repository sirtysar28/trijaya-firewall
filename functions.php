<?php
/**
 * Trijaya Firewall Theme — functions & definitions.
 *
 * @package Trijaya_Firewall
 * @version 1.2.0
 * @author  Tim Trijaya Solution
 * @link    https://trijayasolution.com
 */

defined( 'ABSPATH' ) || exit;

define( 'TRIJAYA_VERSION', '1.4.7' );
define( 'TRIJAYA_DIR', get_template_directory() );
define( 'TRIJAYA_URI', get_template_directory_uri() );

/* -------------------------------------------------------------------------
 * Theme setup
 * ---------------------------------------------------------------------- */
function trijaya_setup(): void {
	load_theme_textdomain( 'trijaya-firewall', TRIJAYA_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 272,
			'width'       => 952,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_theme_support( 'custom-background', array( 'default-color' => 'ffffff' ) );

	// Gambar ukuran tambahan.
	add_image_size( 'trijaya-card', 720, 480, true );
	add_image_size( 'trijaya-wide', 1280, 640, true );

	register_nav_menus(
		array(
			'primary' => __( 'Menu Utama (Header)', 'trijaya-firewall' ),
			'footer'  => __( 'Menu Footer', 'trijaya-firewall' ),
		)
	);

	// Widget area footer.
	register_sidebar(
		array(
			'name'          => __( 'Sidebar Utama', 'trijaya-firewall' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Widget sidebar untuk halaman blog.', 'trijaya-firewall' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: footer column number. */
				'name'          => sprintf( __( 'Footer Kolom %d', 'trijaya-firewall' ), $i ),
				'id'            => 'footer-' . $i,
				/* translators: %d: footer column number. */
				'description'   => sprintf( __( 'Kolom footer #%d. Kolom 1 menggantikan blok brand, kolom 2–4 menggantikan daftar link Layanan/Solutions/Perusahaan. Isi dengan widget “Navigation Menu” agar bisa diedit dari Appearance → Menus.', 'trijaya-firewall' ), $i ),
				'before_widget' => '<div id="%1$s" class="widget tj-footer__widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="tj-footer__title">',
				'after_title'   => '</h3>',
			)
		);
	}
}
add_action( 'after_setup_theme', 'trijaya_setup' );

/* -------------------------------------------------------------------------
 * Assets (fonts Sora + Inter, CSS, JS)
 * ---------------------------------------------------------------------- */

/**
 * Versi aset otomatis dari waktu modifikasi file — cache-busting instan
 * setiap kali file diganti di server, tanpa perlu ubah versi manual.
 */
function trijaya_asset_version( string $file ): string {
	$path  = TRIJAYA_DIR . $file;
	$mtime = file_exists( $path ) ? filemtime( $path ) : false;
	return $mtime ? (string) $mtime : TRIJAYA_VERSION;
}

function trijaya_assets(): void {
	// Google Fonts: Sora (heading) + Inter (body).
	wp_enqueue_style(
		'trijaya-fonts',
		'https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'trijaya-main', TRIJAYA_URI . '/assets/css/main.css', array(), trijaya_asset_version( '/assets/css/main.css' ) );

	wp_enqueue_script( 'trijaya-main', TRIJAYA_URI . '/assets/js/main.js', array(), trijaya_asset_version( '/assets/js/main.js' ), true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Hero slider + animasi hanya di front page / homepage.
	if ( trijaya_is_homepage() ) {
		wp_enqueue_script( 'trijaya-hero', TRIJAYA_URI . '/assets/js/hero.js', array(), trijaya_asset_version( '/assets/js/hero.js' ), true );
	}

	// Data global untuk JS.
	wp_localize_script(
		'trijaya-main',
		'trijayaData',
		array(
			'ajaxUrl'    => esc_url_raw( rest_url( 'trijaya/v1/' ) ),
			'chatNonce'  => wp_create_nonce( 'wp_rest' ),
			'chatOn'     => (bool) get_theme_mod( 'trijaya_chat_enable', true ),
			'scrollHint' => __( 'Gulir untuk eksplor', 'trijaya-firewall' ),
		)
	);

	// Widget AI Chat (Groq).
	if ( get_theme_mod( 'trijaya_chat_enable', true ) && trijaya_chat_configured() ) {
		wp_enqueue_script( 'trijaya-chat', TRIJAYA_URI . '/assets/js/chat.js', array(), trijaya_asset_version( '/assets/js/chat.js' ), true );
		wp_localize_script(
			'trijaya-chat',
			'trijayaChat',
			array(
				'endpoint'  => esc_url_raw( rest_url( 'trijaya/v1/chat' ) ),
				'nonce'     => wp_create_nonce( 'wp_rest' ),
				'name'      => sanitize_text_field( get_theme_mod( 'trijaya_chat_name', 'TJ Assistant' ) ),
				'welcome'   => sanitize_textarea_field( get_theme_mod( 'trijaya_chat_welcome', __( 'Hai! 👋 Ada yang bisa dibantu seputar firewall & network security?', 'trijaya-firewall' ) ) ),
				'quick'     => array_values( array_filter( array(
					sanitize_text_field( get_theme_mod( 'trijaya_chat_quick1', __( 'Harga instalasi firewall?', 'trijaya-firewall' ) ) ),
					sanitize_text_field( get_theme_mod( 'trijaya_chat_quick2', __( 'Renewal license FortiGate', 'trijaya-firewall' ) ) ),
					sanitize_text_field( get_theme_mod( 'trijaya_chat_quick3', __( 'Jasa pentest / audit', 'trijaya-firewall' ) ) ),
				) ) ),
				'poweredBy' => __( 'AI Chat oleh Trijaya Firewall', 'trijaya-firewall' ),
				'placeholder' => __( 'Ketik pesan…', 'trijaya-firewall' ),
			)
		);
	}

	// CSS variabel warna dari Customizer.
	$accent  = sanitize_hex_color( get_theme_mod( 'trijaya_color_accent', '#1d6ff2' ) );
	$accent2 = sanitize_hex_color( get_theme_mod( 'trijaya_color_accent_2', '#00b4d8' ) );
	$css     = sprintf(
		':root{--tj-accent:%s;--tj-accent-2:%s;}',
		$accent ? $accent : '#1d6ff2',
		$accent2 ? $accent2 : '#00b4d8'
	);
	wp_add_inline_style( 'trijaya-main', $css );
}
add_action( 'wp_enqueue_scripts', 'trijaya_assets' );

/* -------------------------------------------------------------------------
 * Guard inline: fix drawer mobile (anti-blur & anti-stale-cache).
 * Dicetak langsung di <head> paling akhir sehingga TIDAK BISA dikalahkan oleh
 * critical CSS / CSS combine / cache plugin optimizer yang masih menyimpan
 * aturan lama. Cari "tj-mobile-fix" di view-source untuk memastikan HTML yang
 * diterima adalah versi terbaru.
 * ---------------------------------------------------------------------- */
function trijaya_inline_mobile_guard(): void {
	echo '<style id="tj-mobile-fix" media="(max-width:1024px)">/* tj-mobile-fix v' . esc_html( TRIJAYA_VERSION ) . " */\n"
		. '.tj-header__bar{-webkit-backdrop-filter:none!important;backdrop-filter:none!important;background:var(--tj-surface,#fff)!important}\n'
		. 'body.tj-nav-open .tj-header{z-index:998!important}\n'
		. '.tj-header__inner>.tj-logo,.tj-header__actions{position:relative;z-index:999}\n'
		. 'body.tj-nav-open .tj-chat{z-index:995}\n'
		. '.tj-nav{position:fixed;top:0;right:0;bottom:0;z-index:997;width:min(360px,92vw);background:var(--tj-surface,#fff);padding:96px 0 0;overflow:hidden;display:flex;flex-direction:column;align-items:stretch;justify-content:flex-start}\n'
		. "</style>\n";
}
add_action( 'wp_head', 'trijaya_inline_mobile_guard', 99 );

/**
 * Apakah halaman ini homepage (front page atau blog index)?
 */
function trijaya_is_homepage(): bool {
	return is_front_page() || ( is_home() && ! is_front_page() );
}

/* -------------------------------------------------------------------------
 * Body classes
 * ---------------------------------------------------------------------- */
function trijaya_body_classes( array $classes ): array {
	$classes[] = 'tj-light-theme';
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'tj-no-sidebar';
	}
	return $classes;
}
add_filter( 'body_class', 'trijaya_body_classes' );

/* -------------------------------------------------------------------------
 * Includes
 * ---------------------------------------------------------------------- */
require TRIJAYA_DIR . '/inc/template-tags.php';
require TRIJAYA_DIR . '/inc/sections.php';
require TRIJAYA_DIR . '/inc/customizer.php';
require TRIJAYA_DIR . '/inc/chat-api.php';
require TRIJAYA_DIR . '/inc/menus.php';
require TRIJAYA_DIR . '/inc/demo-setup.php';
require TRIJAYA_DIR . '/inc/elementor.php';
require TRIJAYA_DIR . '/inc/pwa.php';

/* -------------------------------------------------------------------------
 * Elementor — register theme locations agar tiap bagian bisa di-edit Elementor
 * ---------------------------------------------------------------------- */
function trijaya_register_elementor_locations( object $manager ): void {
	$manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'trijaya_register_elementor_locations' );

/* -------------------------------------------------------------------------
 * Excerpt
 * ---------------------------------------------------------------------- */
function trijaya_excerpt_length( int $length ): int {
	return is_admin() ? $length : 22;
}
add_filter( 'excerpt_length', 'trijaya_excerpt_length' );

function trijaya_excerpt_more( string $more ): string {
	return is_admin() ? $more : '&hellip;';
}
add_filter( 'excerpt_more', 'trijaya_excerpt_more' );

/* -------------------------------------------------------------------------
 * Layout: lebar konten
 * ---------------------------------------------------------------------- */
function trijaya_content_width(): void {
	$GLOBALS['content_width'] = apply_filters( 'trijaya_content_width', 820 );
}
add_action( 'after_setup_theme', 'trijaya_content_width', 0 );
