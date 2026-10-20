<?php
/**
 * Form pencarian.
 *
 * @package Trijaya_Firewall
 */
?>
<form role="search" method="get" class="tj-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="tj-search-field"><?php esc_html_e( 'Cari:', 'trijaya-firewall' ); ?></label>
	<input type="search" id="tj-search-field" class="tj-searchform__input" placeholder="<?php esc_attr_e( 'Cari artikel, layanan…', 'trijaya-firewall' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	<button type="submit" class="tj-searchform__btn" aria-label="<?php esc_attr_e( 'Cari', 'trijaya-firewall' ); ?>">
		<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
	</button>
</form>
