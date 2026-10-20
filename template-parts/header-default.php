<?php
/**
 * Header default theme (fallback jika tidak dioverride Elementor).
 *
 * @package Trijaya_Firewall
 */

$cta_text = get_theme_mod( 'trijaya_contact_wa', '' ) ? __( 'Konsultasi Gratis', 'trijaya-firewall' ) : __( 'Hubungi Kami', 'trijaya-firewall' );
?>
<header id="masthead" class="tj-header" data-sticky="on">
	<div class="tj-header__topbar">
		<div class="tj-container tj-header__topbar-inner">
			<span class="tj-header__tagline">
				<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-3.6 8-10V5l-8-3-8 3v7c0 6.4 8 10 8 10z"/></svg>
				<?php esc_html_e( 'Spesialis Firewall & Network Security Indonesia', 'trijaya-firewall' ); ?>
			</span>
			<span class="tj-header__topinfo">
				<?php if ( get_theme_mod( 'trijaya_contact_phone' ) ) : ?>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', (string) get_theme_mod( 'trijaya_contact_phone' ) ) ); ?>"><?php echo esc_html( get_theme_mod( 'trijaya_contact_phone' ) ); ?></a>
				<?php endif; ?>
				<?php if ( get_theme_mod( 'trijaya_contact_email' ) ) : ?>
					<a href="mailto:<?php echo esc_attr( antispambot( (string) get_theme_mod( 'trijaya_contact_email' ) ) ); ?>"><?php echo esc_html( antispambot( (string) get_theme_mod( 'trijaya_contact_email' ) ) ); ?></a>
				<?php endif; ?>
			</span>
		</div>
	</div>

	<div class="tj-header__bar">
		<div class="tj-container tj-header__inner">
			<?php trijaya_site_logo(); ?>

			<nav id="site-navigation" class="tj-nav" aria-label="<?php esc_attr_e( 'Menu Utama', 'trijaya-firewall' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'tj-nav__menu',
						'container'      => false,
						'depth'          => 3,
						'fallback_cb'    => 'trijaya_default_menu_fallback',
					)
				);
				?>

				<a class="tj-btn tj-btn-primary tj-nav__cta" href="<?php echo esc_url( trijaya_whatsapp_link() ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $cta_text ); ?></a>
			</nav>

			<div class="tj-header__actions">
				<a class="tj-btn tj-btn-primary tj-btn-sm tj-header__cta" href="<?php echo esc_url( trijaya_whatsapp_link() ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $cta_text ); ?></a>
				<button type="button" class="tj-burger" aria-expanded="false" aria-controls="primary-menu" aria-label="<?php esc_attr_e( 'Buka menu', 'trijaya-firewall' ); ?>">
					<span></span><span></span><span></span>
				</button>
			</div>
		</div>
	</div>
</header>
