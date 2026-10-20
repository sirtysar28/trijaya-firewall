<?php
/**
 * Bagian konten dalam loop (kartu post).
 *
 * @package Trijaya_Firewall
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'tj-post tj-reveal' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="tj-post__thumb" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( 'trijaya-card' ); ?>
		</a>
	<?php endif; ?>
	<div class="tj-post__body">
		<div class="tj-post__meta">
			<span><?php echo esc_html( get_the_date() ); ?></span>
			<?php
			$cats = get_the_category_list( ', ' );
			if ( $cats ) :
				?>
				<span class="tj-post__sep">·</span><span><?php echo wp_kses_post( $cats ); ?></span>
			<?php endif; ?>
		</div>
		<h2 class="tj-post__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="tj-post__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<a class="tj-card__more" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Baca selengkapnya', 'trijaya-firewall' ); ?>
			<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
		</a>
	</div>
</article>
