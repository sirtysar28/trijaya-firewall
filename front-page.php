<?php
/**
 * Homepage.
 * Jika front page berupa halaman statis yang dibangun dengan Elementor,
 * tampilkan konten Elementor tersebut. Jika tidak, render section bawaan.
 *
 * @package Trijaya_Firewall
 */

get_header();

if ( trijaya_front_is_elementor() ) :
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
else :
	$order = apply_filters(
		'trijaya_home_section_order',
		array( 'hero', 'brands', 'layanan', 'solutions', 'services', 'why', 'portfolio', 'insight', 'faq', 'kontak' )
	);

	foreach ( $order as $slug ) {
		trijaya_the_section( $slug );
	}
endif;

get_footer();
