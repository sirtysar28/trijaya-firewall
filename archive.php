<?php
/**
 * Template arsip (kategori, tag, dll).
 *
 * @package Trijaya_Firewall
 */

get_header();
?>
<div class="tj-container tj-page-wrap">
	<header class="tj-archive-head">
		<?php the_archive_title( '<h1 class="tj-entry__title">', '</h1>' ); ?>
		<?php the_archive_description( '<div class="tj-archive-desc">', '</div>' ); ?>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="tj-grid tj-grid-3">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', get_post_type() );
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
