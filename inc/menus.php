<?php
/**
 * Struktur menu bawaan Trijaya Firewall.
 *
 * Sumber tunggal struktur menu sesuai dokumen
 * "Menu — Menu Website Trijayafirewall.com":
 *
 * HOME | SOLUTIONS | SERVICES | PRODUCTS | INDUSTRIES | PROJECTS | INSIGHTS | ABOUT US | CONTACT
 * + CTA header: Konsultasi Gratis (WhatsApp).
 *
 * Dipakai untuk:
 * - Fallback wp_nav_menu (menu tampil walau belum dibuat di Appearance → Menus)
 * - Demo Setup (Trijaya Setup) saat membuat halaman + menu otomatis.
 *
 * @package Trijaya_Firewall
 */

defined( 'ABSPATH' ) || exit;

/**
 * Helper: definisi satu item menu.
 *
 * @param string $title    Label menu.
 * @param string $slug     Slug halaman/kategori (relatif terhadap parent).
 * @param array  $children Sub-menu (level berikutnya).
 * @param array  $extra    Atribut tambahan: type (page|category|custom|home),
 *                         root (halaman brand di root), featured, whatsapp.
 * @return array{title: string, slug: string, type: string, children: array}
 */
function trijaya_menu_item( string $title, string $slug = '', array $children = array(), array $extra = array() ): array {
	return array_merge(
		array(
			'title'    => $title,
			'slug'     => $slug,
			'type'     => 'page',
			'children' => $children,
		),
		$extra
	);
}

/**
 * Struktur menu lengkap (3 level) sesuai dokumen Word.
 *
 * @return array<int, array>
 */
function trijaya_menu_tree(): array {
	return array(
		array( 'title' => 'Home', 'slug' => '', 'type' => 'home' ),

		/* ------------------------------------------------ SOLUTIONS */
		trijaya_menu_item(
			'Solutions',
			'solutions',
			array(
				trijaya_menu_item(
					'Network Solution',
					'network-solution',
					array(
						trijaya_menu_item( 'Network Infrastructure', 'network-infrastructure' ),
						trijaya_menu_item( 'LAN & WAN', 'lan-wan' ),
						trijaya_menu_item( 'Wireless Network', 'wireless-network' ),
						trijaya_menu_item( 'Fiber Optic', 'fiber-optic' ),
						trijaya_menu_item( 'Network Monitoring', 'network-monitoring' ),
					)
				),
				trijaya_menu_item(
					'Cyber Security',
					'cyber-security',
					array(
						trijaya_menu_item( 'Firewall', 'firewall' ),
						trijaya_menu_item( 'Endpoint Security', 'endpoint-security' ),
						trijaya_menu_item( 'Network Security', 'network-security' ),
						trijaya_menu_item( 'VPN', 'vpn' ),
						trijaya_menu_item( 'Vulnerability Assessment', 'vulnerability-assessment' ),
						trijaya_menu_item( 'Security Monitoring', 'security-monitoring' ),
					)
				),
				trijaya_menu_item(
					'Server & Data Center',
					'server-data-center',
					array(
						trijaya_menu_item( 'Server', 'server' ),
						trijaya_menu_item( 'Storage & NAS', 'storage-nas' ),
						trijaya_menu_item( 'Virtualization', 'virtualization' ),
						trijaya_menu_item( 'Backup', 'backup' ),
						trijaya_menu_item( 'Data Center', 'data-center' ),
						trijaya_menu_item( 'Disaster Recovery', 'disaster-recovery' ),
					)
				),
				trijaya_menu_item(
					'Physical Security',
					'physical-security',
					array(
						trijaya_menu_item( 'CCTV', 'cctv' ),
						trijaya_menu_item( 'Access Control', 'access-control' ),
						trijaya_menu_item( 'Parking System', 'parking-system' ),
						trijaya_menu_item( 'Fire Alarm', 'fire-alarm' ),
						trijaya_menu_item( 'Security System', 'security-system' ),
					)
				),
				trijaya_menu_item(
					'Software & Digital',
					'software-digital',
					array(
						trijaya_menu_item( 'Custom Application', 'custom-application' ),
						trijaya_menu_item( 'Website Development', 'website-development' ),
						trijaya_menu_item( 'Mobile Application', 'mobile-application' ),
						trijaya_menu_item( 'ERP', 'erp' ),
						trijaya_menu_item( 'Odoo', 'odoo' ),
						trijaya_menu_item( 'System Integration', 'system-integration' ),
					)
				),
			)
		),

		/* ------------------------------------------------ SERVICES */
		trijaya_menu_item(
			'Services',
			'services',
			array(
				trijaya_menu_item( 'IT Support', 'it-support' ),
				trijaya_menu_item( 'Managed IT Services', 'managed-it-services' ),
				trijaya_menu_item( 'IT Support & Managed Services 24/7', 'it-support-managed-services-24-7', array(), array( 'featured' => true ) ),
				trijaya_menu_item( 'Network Installation', 'network-installation' ),
				trijaya_menu_item( 'Firewall Installation', 'firewall-installation' ),
				trijaya_menu_item( 'Server Installation', 'server-installation' ),
				trijaya_menu_item( 'CCTV Installation', 'cctv-installation' ),
				trijaya_menu_item( 'Fiber Optic Splicing', 'fiber-optic-splicing' ),
				trijaya_menu_item( 'IT Maintenance', 'it-maintenance' ),
				trijaya_menu_item( 'Hardware Maintenance', 'hardware-maintenance' ),
				trijaya_menu_item( 'Software Development', 'software-development' ),
				trijaya_menu_item( 'Cloud & Hosting', 'cloud-hosting' ),
				trijaya_menu_item( 'Cyber Security Services', 'cyber-security-services' ),
			)
		),

		/* ------------------------------------------------ PRODUCTS */
		trijaya_menu_item(
			'Products',
			'products',
			array(
				trijaya_menu_item(
					'Network',
					'network',
					array(
						trijaya_menu_item( 'Switch', 'switch' ),
						trijaya_menu_item( 'Router', 'router' ),
						trijaya_menu_item( 'Access Point', 'access-point' ),
						trijaya_menu_item( 'Firewall', 'firewall' ),
						trijaya_menu_item( 'SFP & Transceiver', 'sfp-transceiver' ),
					)
				),
				trijaya_menu_item(
					'Server & Storage',
					'server-storage',
					array(
						trijaya_menu_item( 'Server', 'server' ),
						trijaya_menu_item( 'NAS', 'nas' ),
						trijaya_menu_item( 'Storage', 'storage' ),
						trijaya_menu_item( 'UPS', 'ups' ),
						trijaya_menu_item( 'Rack Server', 'rack-server' ),
					)
				),
				trijaya_menu_item(
					'Security',
					'security',
					array(
						trijaya_menu_item( 'CCTV', 'cctv' ),
						trijaya_menu_item( 'NVR', 'nvr' ),
						trijaya_menu_item( 'Access Control', 'access-control' ),
						trijaya_menu_item( 'Fire Alarm', 'fire-alarm' ),
						trijaya_menu_item( 'X-Ray Security', 'x-ray-security' ),
					)
				),
				trijaya_menu_item(
					'IT Hardware',
					'it-hardware',
					array(
						trijaya_menu_item( 'PC', 'pc' ),
						trijaya_menu_item( 'Laptop', 'laptop' ),
						trijaya_menu_item( 'Monitor', 'monitor' ),
						trijaya_menu_item( 'Printer', 'printer' ),
						trijaya_menu_item( 'Accessories', 'accessories' ),
					)
				),
				trijaya_menu_item(
					'Brand',
					'brand',
					array(
						/* Catatan SEO: brand = landing page tersendiri di root (/fortinet/, /sophos/, dst). */
						trijaya_menu_item( 'Fortinet', 'fortinet', array(), array( 'root' => true ) ),
						trijaya_menu_item( 'Sophos', 'sophos', array(), array( 'root' => true ) ),
						trijaya_menu_item( 'Ruijie', 'ruijie', array(), array( 'root' => true ) ),
						trijaya_menu_item( 'Aruba', 'aruba', array(), array( 'root' => true ) ),
						trijaya_menu_item( 'Juniper', 'juniper', array(), array( 'root' => true ) ),
						trijaya_menu_item( 'Mikrotik', 'mikrotik', array(), array( 'root' => true ) ),
						trijaya_menu_item( 'Synology', 'synology', array(), array( 'root' => true ) ),
						trijaya_menu_item( 'QNAP', 'qnap', array(), array( 'root' => true ) ),
						trijaya_menu_item( 'Nutanix', 'nutanix', array(), array( 'root' => true ) ),
						trijaya_menu_item( 'Palo Alto', 'palo-alto', array(), array( 'root' => true ) ),
					)
				),
			)
		),

		/* ------------------------------------------------ INDUSTRIES */
		trijaya_menu_item(
			'Industries',
			'industries',
			array(
				trijaya_menu_item( 'Manufacturing', 'manufacturing' ),
				trijaya_menu_item( 'Oil & Gas', 'oil-gas' ),
				trijaya_menu_item( 'Mining', 'mining' ),
				trijaya_menu_item( 'Hospital & Healthcare', 'hospital-healthcare' ),
				trijaya_menu_item( 'Education', 'education' ),
				trijaya_menu_item( 'Hospitality', 'hospitality' ),
				trijaya_menu_item( 'Government', 'government' ),
				trijaya_menu_item( 'Banking & Finance', 'banking-finance' ),
				trijaya_menu_item( 'Logistics', 'logistics' ),
				trijaya_menu_item( 'Retail', 'retail' ),
				trijaya_menu_item( 'Construction', 'construction' ),
				trijaya_menu_item( 'Transportation', 'transportation' ),
			)
		),

		/* ------------------------------------------------ PROJECTS */
		trijaya_menu_item(
			'Projects',
			'projects',
			array(
				trijaya_menu_item( 'Network Infrastructure', 'network-infrastructure' ),
				trijaya_menu_item( 'Cyber Security', 'cyber-security' ),
				trijaya_menu_item( 'Data Center', 'data-center' ),
				trijaya_menu_item( 'CCTV & Security', 'cctv-security' ),
				trijaya_menu_item( 'Fiber Optic', 'fiber-optic' ),
				trijaya_menu_item( 'Software Development', 'software-development' ),
				trijaya_menu_item( 'IT Support & Maintenance', 'it-support-maintenance' ),
			)
		),

		/* ------------------------------------------------ INSIGHTS */
		trijaya_menu_item(
			'Insights',
			'insights',
			array(
				/* Kategori blog — arsip artikel per topik (bagus untuk SEO). */
				trijaya_menu_item( 'IT Infrastructure', 'it-infrastructure', array(), array( 'type' => 'category' ) ),
				trijaya_menu_item( 'Network', 'network', array(), array( 'type' => 'category' ) ),
				trijaya_menu_item( 'Cyber Security', 'cyber-security', array(), array( 'type' => 'category' ) ),
				trijaya_menu_item( 'Firewall', 'firewall', array(), array( 'type' => 'category' ) ),
				trijaya_menu_item( 'Server & Storage', 'server-storage', array(), array( 'type' => 'category' ) ),
				trijaya_menu_item( 'CCTV', 'cctv', array(), array( 'type' => 'category' ) ),
				trijaya_menu_item( 'Fiber Optic', 'fiber-optic', array(), array( 'type' => 'category' ) ),
				trijaya_menu_item( 'Software', 'software', array(), array( 'type' => 'category' ) ),
				trijaya_menu_item( 'Cloud', 'cloud', array(), array( 'type' => 'category' ) ),
				trijaya_menu_item( 'Tips & Tutorial', 'tips-tutorial', array(), array( 'type' => 'category' ) ),
				trijaya_menu_item( 'News', 'news', array(), array( 'type' => 'category' ) ),
			)
		),

		/* ------------------------------------------------ ABOUT US */
		trijaya_menu_item(
			'About Us',
			'about-us',
			array(
				trijaya_menu_item( 'About Trijaya Solution', 'about-trijaya-solution' ),
				trijaya_menu_item( 'Vision & Mission', 'vision-mission' ),
				trijaya_menu_item( 'Why Choose Us', 'why-choose-us' ),
				trijaya_menu_item( 'Our Team', 'our-team' ),
				trijaya_menu_item( 'Certifications', 'certifications' ),
				trijaya_menu_item( 'Partners', 'partners' ),
				trijaya_menu_item( 'Clients & Partners', 'clients-partners' ),
				trijaya_menu_item( 'Careers', 'careers' ),
			)
		),

		/* ------------------------------------------------ CONTACT */
		trijaya_menu_item(
			'Contact',
			'contact',
			array(
				trijaya_menu_item( 'Request a Quote', 'request-a-quote' ),
				trijaya_menu_item( 'Technical Consultation', 'technical-consultation' ),
				trijaya_menu_item( 'Service Request', 'service-request' ),
				trijaya_menu_item( 'Location', 'location' ),
				array( 'title' => 'WhatsApp', 'slug' => 'whatsapp', 'type' => 'custom', 'whatsapp' => true ),
			)
		),
	);
}

/**
 * Path URL sebuah item (relatif), mengikuti hierarki parent.
 * Item dengan flag `root` (brand) berdiri sendiri di root, mis. /fortinet/.
 */
function trijaya_menu_item_path( array $item, string $parent_path = '' ): string {
	$slug = isset( $item['slug'] ) ? (string) $item['slug'] : '';
	if ( '' === $slug || ! empty( $item['root'] ) ) {
		return $slug;
	}
	return '' === $parent_path ? $slug : $parent_path . '/' . $slug;
}

/**
 * URL lengkap sebuah item menu.
 */
function trijaya_menu_item_url( array $item, string $parent_path = '' ): string {
	$type = isset( $item['type'] ) ? (string) $item['type'] : 'page';

	if ( 'home' === $type ) {
		return home_url( '/' );
	}

	if ( 'custom' === $type ) {
		if ( ! empty( $item['whatsapp'] ) && function_exists( 'trijaya_whatsapp_link' ) ) {
			return trijaya_whatsapp_link();
		}
		return isset( $item['url'] ) ? (string) $item['url'] : home_url( '/' );
	}

	if ( 'category' === $type ) {
		$term = get_category_by_slug( (string) $item['slug'] );
		if ( $term && ! is_wp_error( $term ) ) {
			$link = get_term_link( $term );
			if ( ! is_wp_error( $link ) ) {
				return (string) $link;
			}
		}
		return home_url( '/category/' . $item['slug'] . '/' );
	}

	return home_url( user_trailingslashit( trijaya_menu_item_path( $item, $parent_path ) ) );
}

/**
 * Cari item menu berdasarkan path lengkap (mis. "solutions/cyber-security").
 */
function trijaya_menu_find( string $path, ?array $items = null, string $parent_path = '' ): ?array {
	if ( null === $items ) {
		$items = trijaya_menu_tree();
	}
	foreach ( $items as $item ) {
		$item_path = trijaya_menu_item_path( $item, $parent_path );
		if ( $item_path === $path ) {
			return $item;
		}
		if ( ! empty( $item['children'] ) ) {
			$found = trijaya_menu_find( $path, $item['children'], $item_path );
			if ( null !== $found ) {
				return $found;
			}
		}
	}
	return null;
}

/**
 * Path URL request saat ini (dengan trailing slash), mis. "/solutions/".
 */
function trijaya_current_path(): string {
	$path = '/';
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$parsed = wp_parse_url( (string) wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH );
		if ( $parsed ) {
			$path = '/' . trim( (string) $parsed, '/' );
			$path = ( '/' === $path ) ? '/' : $path . '/';
		}
	}
	return $path;
}

/**
 * Apakah item (atau salah satu turunannya) adalah halaman aktif saat ini?
 */
function trijaya_menu_item_is_current( array $item, string $parent_path = '' ): bool {
	$type = isset( $item['type'] ) ? (string) $item['type'] : 'page';
	$here = trijaya_current_path();

	if ( 'home' === $type ) {
		return '/' === $here;
	}
	if ( 'category' === $type ) {
		return '/category/' . $item['slug'] . '/' === $here;
	}
	if ( 'page' === $type ) {
		if ( '/' . trijaya_menu_item_path( $item, $parent_path ) . '/' === $here ) {
			return true;
		}
		foreach ( ( isset( $item['children'] ) ? $item['children'] : array() ) as $child ) {
			if ( trijaya_menu_item_is_current( $child, trijaya_menu_item_path( $item, $parent_path ) ) ) {
				return true;
			}
		}
	}
	return false;
}

/**
 * Render daftar item menu (<li>) tanpa pembungkus <ul>.
 */
function trijaya_render_menu_items( array $items, string $parent_path = '' ): void {
	foreach ( $items as $item ) {
		$type     = isset( $item['type'] ) ? (string) $item['type'] : 'page';
		$children = isset( $item['children'] ) ? $item['children'] : array();
		$is_page  = in_array( $type, array( 'page', 'home' ), true );

		$classes = array( 'menu-item', 'menu-item-type-' . ( $is_page ? 'post_type' : $type ) );
		if ( $is_page ) {
			$classes[] = 'menu-item-object-page';
		} elseif ( 'category' === $type ) {
			$classes[] = 'menu-item-object-category';
		} else {
			$classes[] = 'menu-item-object-custom';
		}
		if ( ! empty( $children ) ) {
			$classes[] = 'menu-item-has-children';
		}
		if ( ! empty( $item['featured'] ) ) {
			$classes[] = 'tj-menu-item--featured';
		}
		if ( trijaya_menu_item_is_current( $item, $parent_path ) ) {
			$classes[] = 'current-menu-item current-menu-ancestor current-menu-parent';
		}

		printf(
			'<li class="%1$s"><a href="%2$s">%3$s</a>',
			esc_attr( implode( ' ', $classes ) ),
			esc_url( trijaya_menu_item_url( $item, $parent_path ) ),
			esc_html( (string) $item['title'] )
		);

		if ( ! empty( $children ) ) {
			echo '<ul class="sub-menu">';
			trijaya_render_menu_items( $children, trijaya_menu_item_path( $item, $parent_path ) );
			echo '</ul>';
		}

		echo '</li>';
	}
}

/**
 * Fallback wp_nav_menu: tampilkan struktur menu lengkap bawaan theme
 * ketika belum ada menu yang di-assign di Appearance → Menus.
 *
 * @param array|string $args Argumen wp_nav_menu.
 */
function trijaya_default_menu_fallback( $args = array() ): void {
	$args    = is_array( $args ) ? $args : array();
	$menu_id = ! empty( $args['menu_id'] ) ? (string) $args['menu_id'] : 'primary-menu';
	$menu_ul = ! empty( $args['menu_class'] ) ? (string) $args['menu_class'] : 'tj-nav__menu';

	printf( '<ul id="%s" class="%s">', esc_attr( $menu_id ), esc_attr( $menu_ul ) );
	trijaya_render_menu_items( trijaya_menu_tree() );
	echo '</ul>';
}
