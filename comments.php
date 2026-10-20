<?php
/**
 * Template komentar.
 *
 * @package Trijaya_Firewall
 */

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="tj-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="tj-comments__title">
			<?php
			$count = get_comments_number();
			/* translators: %s: comment count. */
			printf( esc_html( _n( '%s Komentar', '%s Komentar', $count, 'trijaya-firewall' ) ), esc_html( number_format_i18n( $count ) ) );
			?>
		</h2>

		<ol class="tj-comments__list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 44,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'prev_text' => '&larr; ' . __( 'Komentar sebelumnya', 'trijaya-firewall' ),
				'next_text' => __( 'Komentar berikutnya', 'trijaya-firewall' ) . ' &rarr;',
			)
		);

		if ( ! comments_open() ) :
			?>
			<p class="tj-comments__closed"><?php esc_html_e( 'Komentar ditutup.', 'trijaya-firewall' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'         => __( 'Tinggalkan Komentar', 'trijaya-firewall' ),
			'label_submit'        => __( 'Kirim Komentar', 'trijaya-firewall' ),
			'comment_notes_after' => '',
		)
	);
	?>
</div>
