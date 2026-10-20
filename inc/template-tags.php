<?php
/**
 * Template tags & helper.
 *
 * @package Trijaya_Firewall
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tampilkan logo: custom logo → fallback logo bawaan theme.
 */
function trijaya_site_logo( string $size = 'medium' ): void {
	$default = TRIJAYA_URI . '/assets/img/logo.png';

	echo '<a class="tj-logo" href="' . esc_url( home_url( '/' ) ) . '" rel="home" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . '">';

	if ( has_custom_logo() ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		printf(
			'<img src="%s" alt="%s" class="tj-logo-img" loading="lazy" decoding="async">',
			esc_url( wp_get_attachment_image_url( $logo_id, 'full' ) ),
			esc_attr( get_bloginfo( 'name' ) )
		);
	} else {
		printf(
			'<img src="%s" alt="%s" class="tj-logo-img" decoding="async">',
			esc_url( $default ),
			esc_attr( get_bloginfo( 'name' ) )
		);
	}

	echo '</a>';
}

/**
 * Hero slides dari Customizer. Return array slide aktif.
 *
 * @return array<int, array<string, string>>
 */
function trijaya_get_hero_slides(): array {
	$slides = array();

	for ( $i = 1; $i <= 5; $i++ ) {
		$title = trim( (string) get_theme_mod( "trijaya_hero_{$i}_title" ) );
		$use   = (bool) get_theme_mod( "trijaya_hero_{$i}_use", $i <= 3 );

		if ( '' === $title || ! $use ) {
			continue;
		}

		$default_sub = '';
		if ( 1 === $i ) {
			$default_sub = __( 'Solusi instalasi, konfigurasi, maintenance, renewal license, dan managed service firewall untuk kebutuhan bisnis dan enterprise.', 'trijaya-firewall' );
		}

		$slides[ $i ] = array(
			'title'    => $title,
			'subtitle' => trim( (string) get_theme_mod( "trijaya_hero_{$i}_subtitle", $default_sub ) ),
			'btn1_txt' => trim( (string) get_theme_mod( "trijaya_hero_{$i}_btn1_txt", __( 'Konsultasi Gratis', 'trijaya-firewall' ) ) ),
			'btn1_url' => trim( (string) get_theme_mod( "trijaya_hero_{$i}_btn1_url", '#kontak' ) ),
			'btn2_txt' => trim( (string) get_theme_mod( "trijaya_hero_{$i}_btn2_txt", __( 'Lihat Layanan', 'trijaya-firewall' ) ) ),
			'btn2_url' => trim( (string) get_theme_mod( "trijaya_hero_{$i}_btn2_url", '#layanan' ) ),
			'image'    => trim( (string) get_theme_mod( "trijaya_hero_{$i}_image", '' ) ),
		);
	}

	// Slide 1 wajib ada — fallback konten dari Workflow.
	if ( empty( $slides ) ) {
		$slides[1] = array(
			'title'    => __( 'Jasa Firewall & Network Security Indonesia', 'trijaya-firewall' ),
			'subtitle' => __( 'Solusi instalasi, konfigurasi, maintenance, renewal license, dan managed service firewall untuk kebutuhan bisnis dan enterprise.', 'trijaya-firewall' ),
			'btn1_txt' => __( 'Konsultasi Gratis', 'trijaya-firewall' ),
			'btn1_url' => '#kontak',
			'btn2_txt' => __( 'Lihat Layanan', 'trijaya-firewall' ),
			'btn2_url' => '#layanan',
			'image'    => '',
		);
	}

	return $slides;
}

/**
 * Ambil konten section: jika ada page yang DIBANGUN DENGAN ELEMENTOR
 * dengan slug tertentu, pakai itu sebagai pengganti konten default.
 * Jika tidak, kembalikan string kosong → template memakai konten default.
 */
function trijaya_get_elementor_section( string $slug ): string {
	$page = get_page_by_path( $slug );
	if ( ! $page instanceof WP_Post ) {
		return '';
	}

	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		return '';
	}

	$document = \Elementor\Plugin::$instance->documents->get( $page->ID );
	if ( ! $document || ! $document->is_built_with_elementor() ) {
		return '';
	}

	return trim( (string) \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $page->ID ) );
}

/**
 * Cek apakah front page dibangun dengan Elementor.
 */
function trijaya_front_is_elementor(): bool {
	if ( ! is_page() || ! class_exists( '\Elementor\Plugin' ) ) {
		return false;
	}
	$doc = \Elementor\Plugin::$instance->documents->get( get_queried_object_id() );
	return $doc ? $doc->is_built_with_elementor() : false;
}

/**
 * Render bagian "eyebrow" (label kecil di atas judul section).
 */
function trijaya_section_head( string $eyebrow, string $title, string $desc = '' ): void {
	echo '<div class="tj-section-head tj-reveal">';
	if ( $eyebrow ) {
		echo '<span class="tj-eyebrow">' . esc_html( $eyebrow ) . '</span>';
	}
	echo '<h2 class="tj-section-title">' . esc_html( $title ) . '</h2>';
	if ( $desc ) {
		echo '<p class="tj-section-desc">' . esc_html( $desc ) . '</p>';
	}
	echo '</div>';
}

/**
 * Nomor WhatsApp untuk tombol CTA.
 */
function trijaya_whatsapp_link( string $text = '' ): string {
	$number = preg_replace( '/[^0-9]/', '', (string) get_theme_mod( 'trijaya_contact_wa', '6281234567890' ) );
	$text   = $text ? $text : __( 'Halo, saya ingin konsultasi kebutuhan network security.', 'trijaya-firewall' );
	return 'https://wa.me/' . $number . '?text=' . rawurlencode( $text );
}
