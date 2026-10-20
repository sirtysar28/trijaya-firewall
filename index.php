<?php
/**
 * Fallback template utama.
 *
 * @package Trijaya_Firewall
 */

get_header();
?>
<div class="tj-container tj-page-wrap">
	<?php if ( have_posts() ) : ?>
		<div class="tj-content-area<?php echo is_active_sidebar( 'sidebar-1' ) ? ' has-sidebar' : ''; ?>">
			<div class="tj-loop">
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
		</div>
		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<aside class="tj-sidebar"><?php dynamic_sidebar( 'sidebar-1' ); ?></aside>
		<?php endif; ?>
	<?php else : ?>
		<div class="tj-noproblem">
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		</div>
	<?php endif; ?>
</div>
<?php
get_footer();
