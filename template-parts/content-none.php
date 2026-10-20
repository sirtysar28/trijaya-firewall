<?php
/**
 * Konten "tidak ditemukan".
 *
 * @package Trijaya_Firewall
 */
?>
<section class="tj-no-results">
	<header class="tj-section-head">
		<h2 class="tj-section-title"><?php esc_html_e( 'Tidak Ada Konten', 'trijaya-firewall' ); ?></h2>
	</header>
	<div class="tj-section-desc">
		<p><?php esc_html_e( 'Maaf, tidak ada konten yang cocok dengan pencarian Anda. Silakan coba kata kunci lain.', 'trijaya-firewall' ); ?></p>
	</div>
	<?php get_search_form(); ?>
</section>
