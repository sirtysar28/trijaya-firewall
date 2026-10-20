<?php
/**
 * Dukungan Elementor.
 * - Theme locations (header/footer/single/archive bisa dioverride Elementor)
 * - Shortcode untuk tiap section homepage agar bisa diletakkan di Elementor
 *
 * @package Trijaya_Firewall
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cek apakah Elementor aktif.
 */
function trijaya_elementor_active(): bool {
	return class_exists( '\Elementor\Plugin' );
}

/**
 * Render header: pakai Elementor location bila ada, jika tidak pakai default.
 */
function trijaya_header_render(): void {
	if ( trijaya_elementor_active() && function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
		return;
	}
	get_template_part( 'template-parts/header-default' );
}

/**
 * Render footer: pakai Elementor location bila ada, jika tidak pakai default.
 */
function trijaya_footer_render(): void {
	if ( trijaya_elementor_active() && function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) ) {
		return;
	}
	get_template_part( 'template-parts/footer-default' );
}

/**
 * Shortcode section homepage untuk dipakai di Elementor.
 * Contoh: [trijaya_section slug="layanan"]
 */
function trijaya_section_shortcode( array $atts ): string {
	$atts = shortcode_atts( array( 'slug' => '' ), $atts, 'trijaya_section' );
	$slug = sanitize_key( $atts['slug'] );
	if ( '' === $slug ) {
		return '';
	}

	$sections = trijaya_home_sections();
	if ( ! isset( $sections[ $slug ] ) ) {
		return '';
	}

	ob_start();
	call_user_func( $sections[ $slug ]['callback'] );
	return (string) ob_get_clean();
}
add_shortcode( 'trijaya_section', 'trijaya_section_shortcode' );

/**
 * Daftar section homepage: slug => callback.
 * Dipakai oleh front-page.php & shortcode Elementor.
 *
 * @return array<string, array{callback: callable}>
 */
function trijaya_home_sections(): array {
	static $sections = null;
	if ( null !== $sections ) {
		return $sections;
	}

	$sections = array(
		'hero'       => array( 'callback' => 'trijaya_render_hero' ),
		'brands'     => array( 'callback' => 'trijaya_render_brands' ),
		'layanan'    => array( 'callback' => 'trijaya_render_layanan' ),
		'solutions'  => array( 'callback' => 'trijaya_render_solutions' ),
		'services'   => array( 'callback' => 'trijaya_render_services' ),
		'why'        => array( 'callback' => 'trijaya_render_why' ),
		'portfolio'  => array( 'callback' => 'trijaya_render_portfolio' ),
		'insight'    => array( 'callback' => 'trijaya_render_insight' ),
		'faq'        => array( 'callback' => 'trijaya_render_faq' ),
		'kontak'     => array( 'callback' => 'trijaya_render_kontak' ),
	);

	return apply_filters( 'trijaya_home_sections', $sections );
}

/**
 * Tampilkan satu section (dipakai front-page.php).
 */
function trijaya_the_section( string $slug ): void {
	$sections = trijaya_home_sections();
	if ( ! isset( $sections[ $slug ] ) ) {
		return;
	}
	call_user_func( $sections[ $slug ]['callback'] );
}
