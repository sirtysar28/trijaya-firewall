<?php
/**
 * Template post tunggal.
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
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'tj-entry tj-entry--single' ); ?>>
			<header class="tj-entry__header">
				<div class="tj-entry__meta">
					<?php
					printf(
						/* translators: 1: date, 2: author. */
						esc_html__( '%1$s · oleh %2$s', 'trijaya-firewall' ),
						esc_html( get_the_date() ),
						esc_html( get_the_author() )
					);
					?>
				</div>
				<h1 class="tj-entry__title"><?php the_title(); ?></h1>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
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

			<?php the_tags( '<footer class="tj-entry__tags">', ' ', '</footer>' ); ?>
		</article>

		<nav class="tj-post-nav" aria-label="<?php esc_attr_e( 'Navigasi post', 'trijaya-firewall' ); ?>">
			<?php
			previous_post_link( '<div class="tj-post-nav__prev"><span>&larr; ' . esc_html__( 'Post sebelumnya', 'trijaya-firewall' ) . '</span>%link</div>', '%title' );
			next_post_link( '<div class="tj-post-nav__next"><span>' . esc_html__( 'Post berikutnya', 'trijaya-firewall' ) . ' &rarr;</span>%link</div>', '%title' );
			?>
		</nav>

		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	endwhile;
	?>
</div>
<?php
get_footer();
