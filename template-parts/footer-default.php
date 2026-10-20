<?php
/**
 * Footer default theme (fallback jika tidak dioverride Elementor).
 *
 * Semua kolom footer bisa diedit dari WP Admin:
 * - Kolom 1 (brand)  : dioverride widget "Footer Kolom 1".
 * - Kolom 2 (Layanan) : dioverride widget "Footer Kolom 2".
 * - Kolom 3 (Solutions)     : dioverride widget "Footer Kolom 3".
 * - Kolom 4 (Perusahaan)    : dioverride widget "Footer Kolom 4".
 *   Isi widget dengan "Navigation Menu" (atau widget apa pun) —
 *   link-nya diatur dari Appearance → Menus.
 * - Menu baris bawah : location "Footer" via Appearance → Menus.
 *
 * Jika widget kosong → tampilan default theme tetap dipakai,
 * sehingga tampilan tidak berubah sebelum user mengedit.
 *
 * @package Trijaya_Firewall
 */

$has_footer_menu = has_nav_menu( 'footer' );

$footer_cols = array(
	array(
		'id'    => 'footer-2',
		'title' => __( 'Layanan', 'trijaya-firewall' ),
		'links' => array(
			array( '/services/it-support/', __( 'IT Support', 'trijaya-firewall' ) ),
			array( '/services/managed-it-services/', __( 'Managed IT Services', 'trijaya-firewall' ) ),
			array( '/services/network-installation/', __( 'Network Installation', 'trijaya-firewall' ) ),
			array( '/services/cctv-installation/', __( 'CCTV Installation', 'trijaya-firewall' ) ),
			array( '/services/it-maintenance/', __( 'IT Maintenance', 'trijaya-firewall' ) ),
			array( '/services/cloud-hosting/', __( 'Cloud & Hosting', 'trijaya-firewall' ) ),
		),
	),
	array(
		'id'    => 'footer-3',
		'title' => __( 'Solutions', 'trijaya-firewall' ),
		'links' => array(
			array( '/solutions/network-solution/', __( 'Network Solution', 'trijaya-firewall' ) ),
			array( '/solutions/cyber-security/', __( 'Cyber Security', 'trijaya-firewall' ) ),
			array( '/solutions/server-data-center/', __( 'Server & Data Center', 'trijaya-firewall' ) ),
			array( '/solutions/physical-security/', __( 'Physical Security', 'trijaya-firewall' ) ),
			array( '/solutions/software-digital/', __( 'Software & Digital', 'trijaya-firewall' ) ),
		),
	),
	array(
		'id'    => 'footer-4',
		'title' => __( 'Perusahaan', 'trijaya-firewall' ),
		'links' => array(
			array( '/about-us/', __( 'About Us', 'trijaya-firewall' ) ),
			array( '/projects/', __( 'Projects', 'trijaya-firewall' ) ),
			array( '/industries/', __( 'Industries', 'trijaya-firewall' ) ),
			array( '/insights/', __( 'Insights', 'trijaya-firewall' ) ),
			array( '/contact/', __( 'Contact', 'trijaya-firewall' ) ),
			array( '/contact/request-a-quote/', __( 'Request a Quote', 'trijaya-firewall' ) ),
		),
	),
);
?>
<footer id="colophon" class="tj-footer">
	<div class="tj-container">
		<div class="tj-footer__grid">

			<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<div class="tj-footer__col tj-footer__col--widgets">
					<?php dynamic_sidebar( 'footer-1' ); ?>
				</div>
			<?php else : ?>
				<div class="tj-footer__brand">
					<?php trijaya_site_logo(); ?>
					<p class="tj-footer__desc"><?php echo esc_html( get_theme_mod( 'trijaya_footer_text', __( 'Spesialis Firewall, Network Security & Cyber Security untuk bisnis dan enterprise di Indonesia.', 'trijaya-firewall' ) ) ); ?></p>
					<div class="tj-footer__contact">
						<?php if ( get_theme_mod( 'trijaya_contact_phone' ) ) : ?>
							<span>📞 <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', (string) get_theme_mod( 'trijaya_contact_phone' ) ) ); ?>"><?php echo esc_html( get_theme_mod( 'trijaya_contact_phone' ) ); ?></a></span>
						<?php endif; ?>
						<?php if ( get_theme_mod( 'trijaya_contact_email' ) ) : ?>
							<span>✉️ <a href="mailto:<?php echo esc_attr( antispambot( (string) get_theme_mod( 'trijaya_contact_email' ) ) ); ?>"><?php echo esc_html( antispambot( (string) get_theme_mod( 'trijaya_contact_email' ) ) ); ?></a></span>
						<?php endif; ?>
						<?php if ( get_theme_mod( 'trijaya_contact_address' ) ) : ?>
							<span>📍 <?php echo esc_html( get_theme_mod( 'trijaya_contact_address' ) ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php foreach ( $footer_cols as $col ) : ?>
				<div class="tj-footer__col<?php echo is_active_sidebar( $col['id'] ) ? ' tj-footer__col--widgets' : ''; ?>">
					<?php if ( is_active_sidebar( $col['id'] ) ) : ?>
						<?php dynamic_sidebar( $col['id'] ); ?>
					<?php else : ?>
						<h3 class="tj-footer__title"><?php echo esc_html( $col['title'] ); ?></h3>
						<ul class="tj-footer__links">
							<?php foreach ( $col['links'] as $link ) : ?>
								<li><a href="<?php echo esc_url( home_url( $link[0] ) ); ?>"><?php echo esc_html( $link[1] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $has_footer_menu ) : ?>
			<nav class="tj-footer__menu" aria-label="<?php esc_attr_e( 'Menu Footer', 'trijaya-firewall' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_class'     => 'tj-footer__menu-list',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>
		<?php endif; ?>

		<div class="tj-footer__bottom">
			<span class="tj-footer__copy">&copy; <?php echo esc_html( (string) gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Seluruh hak cipta dilindungi.', 'trijaya-firewall' ); ?></span>
			<span class="tj-footer__credit">
				<?php esc_html_e( 'Powered by', 'trijaya-firewall' ); ?>
				<a href="https://trijayasolution.co.id" target="_blank" rel="noopener nofollow">Trijaya Solution</a>
			</span>
		</div>
	</div>
</footer>
