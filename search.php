<?php
/**
 * Template hasil pencarian.
 *
 * @package Trijaya_Firewall
 */

get_header();
?>
<div class="tj-container tj-page-wrap">
	<header class="tj-archive-head">
		<h1 class="tj-entry__title">
			<?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Hasil pencarian: %s', 'trijaya-firewall' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
			?>
		</h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="tj-grid tj-grid-3">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'search' );
			endwhile;
			?>
		</div>
		<?php
		the_posts_pagination(
			array(
				'mid_size'  => 2,
				'prev_text' => '&larr; ' . __( 'Sebelumnya', 'trijaya-firewall' ),
				'next_text' => __( 'Berikutnya', 'trijaya-firewall' ) . ' &rarr;',
			)
		);
		?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</div>
<?php
get_footer();
