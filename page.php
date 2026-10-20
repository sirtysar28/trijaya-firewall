<?php
/**
 * Template halaman statis.
 *
 * @package Trijaya_Firewall
 */

get_header();
?>
<div class="tj-container tj-page-wrap">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'tj-entry' ); ?>>
			<?php if ( apply_filters( 'trijaya_show_page_title', ! trijaya_front_is_elementor() ) ) : ?>
				<header class="tj-entry__header">
					<h1 class="tj-entry__title"><?php the_title(); ?></h1>
				</header>
			<?php endif; ?>
			<?php if ( has_post_thumbnail() && ! trijaya_front_is_elementor() ) : ?>
				<div class="tj-entry__thumb"><?php the_post_thumbnail( 'trijaya-wide' ); ?></div>
			<?php endif; ?>
			<div class="tj-entry__content">
				<?php
				the_content();
				wp_link_pages(
					array(
						'before' => '<nav class="tj-page-links">' . esc_html__( 'Halaman:', 'trijaya-firewall' ),
						'after'  => '</nav>',
					)
				);
				?>
			</div>
		</article>
		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	endwhile;
	?>
</div>
<?php
get_footer();
