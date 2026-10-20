<?php
/**
 * Template 404.
 *
 * @package Trijaya_Firewall
 */

get_header();
?>
<div class="tj-container tj-page-wrap tj-center">
	<div class="tj-404">
		<span class="tj-404__code" aria-hidden="true">4<i>0</i>4</span>
		<h1><?php esc_html_e( 'Halaman Tidak Ditemukan', 'trijaya-firewall' ); ?></h1>
		<p><?php esc_html_e( 'Sepertinya halaman yang Anda cari sudah dipindahkan atau tidak pernah ada. Silakan periksa URL atau gunakan pencarian.', 'trijaya-firewall' ); ?></p>
		<?php get_search_form(); ?>
		<a class="tj-btn tj-btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Kembali ke Beranda', 'trijaya-firewall' ); ?></a>
	</div>
</div>
<?php
get_footer();
