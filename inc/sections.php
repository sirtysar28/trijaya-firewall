<?php
/**
 * Render seluruh section homepage Trijaya Firewall.
 * Struktur mengikuti Workflow.txt (menu & konten SEO).
 * Setiap section bisa di-override dengan halaman Elementor ber-slug sama.
 *
 * @package Trijaya_Firewall
 */

defined( 'ABSPATH' ) || exit;

/**
 * Helper: bungkus konten section; jika ada page Elementor dengan slug sama,
 * tampilkan konten Elementor tersebut sebagai pengganti konten default.
 */
function trijaya_section_render( string $slug, callable $default_cb ): void {
	$elementor = trijaya_get_elementor_section( $slug );
	if ( '' !== $elementor ) {
		echo '<section id="' . esc_attr( $slug ) . '" class="tj-section tj-section-elementor">';
		echo $elementor; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- konten Elementor/halaman sudah aman.
		echo '</section>';
		return;
	}
	call_user_func( $default_cb );
}

/* =========================================================================
 * 1. HERO — statis (animasi) bila 1 slide, slider otomatis bila > 1
 * ====================================================================== */
function trijaya_render_hero(): void {
	$slides = trijaya_get_hero_slides();
	$count  = count( $slides );
	$is_slider = $count > 1;
	?>
	<section id="hero" class="tj-hero<?php echo $is_slider ? ' tj-hero-slider' : ''; ?>" data-slider="<?php echo $is_slider ? 'on' : 'off'; ?>">
		<div class="tj-hero-bg" aria-hidden="true">
			<span class="tj-blob tj-blob-1"></span>
			<span class="tj-blob tj-blob-2"></span>
			<span class="tj-blob tj-blob-3"></span>
			<span class="tj-grid-overlay"></span>
			<span class="tj-particles" id="tj-particles"></span>
		</div>

		<div class="tj-container tj-hero__inner">
			<div class="tj-hero__slides<?php echo $is_slider ? ' is-slider' : ''; ?>">
				<?php $i = 0; ?>
				<?php foreach ( $slides as $slide ) : ?>
					<div class="tj-hero__slide<?php echo 0 === $i ? ' is-active' : ''; ?>" data-index="<?php echo esc_attr( (string) $i ); ?>">
						<div class="tj-hero__content">
							<span class="tj-hero__badge tj-anim tj-anim-1">
								<span class="tj-badge-dot"></span>
								<?php esc_html_e( 'Trusted Network Security Partner', 'trijaya-firewall' ); ?>
							</span>
							<h1 class="tj-hero__title tj-anim tj-anim-2"><?php echo esc_html( $slide['title'] ); ?></h1>
							<p class="tj-hero__subtitle tj-anim tj-anim-3"><?php echo esc_html( $slide['subtitle'] ); ?></p>
							<div class="tj-hero__actions tj-anim tj-anim-4">
								<?php if ( $slide['btn1_txt'] ) : ?>
									<a class="tj-btn tj-btn-primary tj-btn-lg" href="<?php echo esc_url( $slide['btn1_url'] ); ?>">
										<?php echo esc_html( $slide['btn1_txt'] ); ?>
										<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
									</a>
								<?php endif; ?>
								<?php if ( $slide['btn2_txt'] ) : ?>
									<a class="tj-btn tj-btn-ghost tj-btn-lg" href="<?php echo esc_url( $slide['btn2_url'] ); ?>"><?php echo esc_html( $slide['btn2_txt'] ); ?></a>
								<?php endif; ?>
							</div>
							<ul class="tj-hero__points tj-anim tj-anim-5">
								<li><?php esc_html_e( 'Certified Engineer', 'trijaya-firewall' ); ?></li>
								<li><?php esc_html_e( 'Support 24/7', 'trijaya-firewall' ); ?></li>
								<li><?php esc_html_e( 'Enterprise & SMB', 'trijaya-firewall' ); ?></li>
							</ul>
						</div>
						<div class="tj-hero__visual tj-anim tj-anim-3" aria-hidden="true">
							<?php if ( $slide['image'] ) : ?>
								<div class="tj-hero__img-wrap"><img src="<?php echo esc_url( $slide['image'] ); ?>" alt="" loading="lazy" decoding="async"></div>
							<?php endif; ?>
							<div class="tj-hero__shield<?php echo $slide['image'] ? ' has-img' : ''; ?>">
								<span class="tj-ring tj-ring-1"></span>
								<span class="tj-ring tj-ring-2"></span>
								<span class="tj-ring tj-ring-3"></span>
								<svg class="tj-shield-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-3.6 8-10V5l-8-3-8 3v7c0 6.4 8 10 8 10z"/><path d="M9 11.5l2 2 4-4.5"/></svg>
								<span class="tj-float tj-float-1"><?php esc_html_e( 'FortiGate', 'trijaya-firewall' ); ?></span>
								<span class="tj-float tj-float-2"><?php esc_html_e( 'Palo Alto', 'trijaya-firewall' ); ?></span>
								<span class="tj-float tj-float-3"><?php esc_html_e( 'Sophos', 'trijaya-firewall' ); ?></span>
							</div>
						</div>
					</div>
					<?php $i++; ?>
				<?php endforeach; ?>
			</div>

			<?php if ( $is_slider ) : ?>
			<div class="tj-hero__nav" role="group" aria-label="<?php esc_attr_e( 'Navigasi slider', 'trijaya-firewall' ); ?>">
				<button type="button" class="tj-hero__arrow tj-hero__prev" aria-label="<?php esc_attr_e( 'Slide sebelumnya', 'trijaya-firewall' ); ?>">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
				</button>
				<div class="tj-hero__dots" role="tablist"></div>
				<button type="button" class="tj-hero__arrow tj-hero__next" aria-label="<?php esc_attr_e( 'Slide berikutnya', 'trijaya-firewall' ); ?>">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
				</button>
			</div>
			<span class="tj-hero__progress" aria-hidden="true"></span>
			<?php endif; ?>
		</div>

		<span class="tj-hero__scroll" aria-hidden="true">
			<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M6 13l6 6 6-6"/></svg>
			<?php esc_html_e( 'Scroll', 'trijaya-firewall' ); ?>
		</span>
	</section>
	<?php
}

/* =========================================================================
 * 2. BRAND STRIP — marquee brand
 * ====================================================================== */
function trijaya_render_brands(): void {
	trijaya_section_render(
		'brands',
		static function (): void {
			$brands = array( 'Fortinet', 'Palo Alto', 'Sophos', 'Cisco', 'Aruba', 'Ruijie', 'Mikrotik', 'Juniper' );
			?>
			<section id="brands" class="tj-brands" aria-label="<?php esc_attr_e( 'Partner & Brand', 'trijaya-firewall' ); ?>">
				<div class="tj-container">
					<p class="tj-brands__label"><?php esc_html_e( 'Technology Partner Resmi & Terpercaya', 'trijaya-firewall' ); ?></p>
					<div class="tj-marquee">
						<div class="tj-marquee__track">
							<?php for ( $r = 0; $r < 2; $r++ ) : ?>
								<?php foreach ( $brands as $brand ) : ?>
									<span class="tj-marquee__item"><?php echo esc_html( $brand ); ?></span>
								<?php endforeach; ?>
							<?php endfor; ?>
						</div>
					</div>
				</div>
			</section>
			<?php
		}
	);
}

/* =========================================================================
 * 3. LAYANAN UTAMA — 4 pilar sesuai Workflow
 * ====================================================================== */
function trijaya_render_layanan(): void {
	trijaya_section_render(
		'layanan',
		static function (): void {
			$icon_firewall = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-3.6 8-10V5l-8-3-8 3v7c0 6.4 8 10 8 10z"/><path d="M9 11.5l2 2 4-4.5"/></svg>';
			$icon_netsec   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.5 4 5.6 4 9s-1.5 6.5-4 9c-2.5-2.5-4-5.6-4-9s1.5-6.5 4-9z"/></svg>';
			$icon_cyber    = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l8 4v6c0 5-3.4 8.6-8 10-4.6-1.4-8-5-8-10V6l8-4z"/><path d="M12 8v4"/><circle cx="12" cy="15" r="0.5" fill="currentColor"/></svg>';
			$icon_infra    = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="6" rx="2"/><rect x="3" y="14" width="18" height="6" rx="2"/><path d="M7 7h.01M7 17h.01M12 10v4"/></svg>';

			$cards = array(
				array(
					'icon'  => $icon_firewall,
					'title' => __( 'Firewall', 'trijaya-firewall' ),
					'desc'  => __( 'Instalasi, konfigurasi, maintenance & renewal license firewall untuk bisnis dan enterprise.', 'trijaya-firewall' ),
					'items' => array(
						__( 'Fortinet / FortiGate', 'trijaya-firewall' ),
						__( 'Palo Alto', 'trijaya-firewall' ),
						__( 'Sophos', 'trijaya-firewall' ),
						__( 'Ruijie & Mikrotik', 'trijaya-firewall' ),
						__( 'Firewall License Renewal', 'trijaya-firewall' ),
					),
					'link'  => home_url( '/solutions/cyber-security/firewall/' ),
				),
				array(
					'icon'  => $icon_netsec,
					'title' => __( 'Network Security', 'trijaya-firewall' ),
					'desc'  => __( 'Assessment, audit, hardening, VPN & monitoring untuk keamanan jaringan perusahaan.', 'trijaya-firewall' ),
					'items' => array(
						__( 'Security Assessment', 'trijaya-firewall' ),
						__( 'Network Security Audit', 'trijaya-firewall' ),
						__( 'VPN: Site-to-Site, Remote, SSL', 'trijaya-firewall' ),
						__( 'Network Hardening', 'trijaya-firewall' ),
						__( 'Network Monitoring', 'trijaya-firewall' ),
					),
					'link'  => home_url( '/solutions/cyber-security/network-security/' ),
				),
				array(
					'icon'  => $icon_cyber,
					'title' => __( 'Cyber Security', 'trijaya-firewall' ),
					'desc'  => __( 'Lindungi bisnis dari ancaman siber dengan assessment, pentest & endpoint security.', 'trijaya-firewall' ),
					'items' => array(
						__( 'Vulnerability Assessment', 'trijaya-firewall' ),
						__( 'Penetration Testing', 'trijaya-firewall' ),
						__( 'Endpoint Security', 'trijaya-firewall' ),
						__( 'Email & Web Security', 'trijaya-firewall' ),
						__( 'Incident Response', 'trijaya-firewall' ),
					),
					'link'  => home_url( '/solutions/cyber-security/' ),
				),
				array(
					'icon'  => $icon_infra,
					'title' => __( 'Network Infrastructure', 'trijaya-firewall' ),
					'desc'  => __( 'Instalasi & pembangunan infrastruktur jaringan dari kabel hingga fiber optic.', 'trijaya-firewall' ),
					'items' => array(
						__( 'Network Installation', 'trijaya-firewall' ),
						__( 'Switch, Router & Access Point', 'trijaya-firewall' ),
						__( 'WiFi / Wireless', 'trijaya-firewall' ),
						__( 'Fiber Optic', 'trijaya-firewall' ),
						__( 'Structured Cabling', 'trijaya-firewall' ),
					),
					'link'  => home_url( '/solutions/network-solution/network-infrastructure/' ),
				),
			);
			?>
			<section id="layanan" class="tj-section">
				<div class="tj-container">
					<?php trijaya_section_head(
						__( 'Layanan Kami', 'trijaya-firewall' ),
						__( 'Solusi Keamanan Jaringan End-to-End', 'trijaya-firewall' ),
						__( 'Dari instalasi firewall hingga managed service — semua kebutuhan network & cyber security perusahaan Anda dalam satu tempat.', 'trijaya-firewall' )
					); ?>
					<div class="tj-grid tj-grid-4 tj-cards">
						<?php foreach ( $cards as $card ) : ?>
							<article class="tj-card tj-reveal">
								<span class="tj-card__icon"><?php echo $card['icon']; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<h3 class="tj-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
								<p class="tj-card__desc"><?php echo esc_html( $card['desc'] ); ?></p>
								<ul class="tj-card__list">
									<?php foreach ( $card['items'] as $item ) : ?>
										<li><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 13l4 4 10-11"/></svg><?php echo esc_html( $item ); ?></li>
									<?php endforeach; ?>
								</ul>
								<a class="tj-card__more" href="<?php echo esc_url( $card['link'] ); ?>">
									<?php esc_html_e( 'Selengkapnya', 'trijaya-firewall' ); ?>
									<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
								</a>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
			<?php
		}
	);
}

/* =========================================================================
 * 4. SOLUTIONS / BRAND
 * ====================================================================== */
function trijaya_render_solutions(): void {
	trijaya_section_render(
		'solutions',
		static function (): void {
			$brands = array(
				array( 'Fortinet', __( 'FortiGate Security Fabric: NGFW, SD-WAN & zero trust.', 'trijaya-firewall' ), home_url( '/fortinet/' ) ),
				array( 'Palo Alto Networks', __( 'Next-Gen Firewall & Security Operating Platform.', 'trijaya-firewall' ), home_url( '/palo-alto/' ) ),
				array( 'Sophos', __( 'Synchronized security: firewall, endpoint & email.', 'trijaya-firewall' ), home_url( '/sophos/' ) ),
				array( 'Cisco', __( 'Jaringan & keamanan enterprise kelas dunia.', 'trijaya-firewall' ), home_url( '/cisco/' ) ),
				array( 'Aruba', __( 'WiFi enterprise & edge-to-cloud security.', 'trijaya-firewall' ), home_url( '/aruba/' ) ),
				array( 'Ruijie', __( 'Jaringan & firewall dengan value terbaik.', 'trijaya-firewall' ), home_url( '/ruijie/' ) ),
				array( 'Mikrotik', __( 'Router, switch & firewall RouterOS untuk segala skala.', 'trijaya-firewall' ), home_url( '/mikrotik/' ) ),
				array( 'Juniper', __( 'Infrastruktur jaringan & security yang teruji.', 'trijaya-firewall' ), home_url( '/juniper/' ) ),
			);
			?>
			<section id="solutions" class="tj-section tj-section--alt">
				<div class="tj-container">
					<?php trijaya_section_head(
						__( 'Brand / Solutions', 'trijaya-firewall' ),
						__( 'Resmi & Berpengalaman di Berbagai Brand', 'trijaya-firewall' ),
						__( 'Kami menginstalasi, mengonfigurasi, dan merawat perangkat dari brand terkemuka dunia.', 'trijaya-firewall' )
					); ?>
					<div class="tj-grid tj-grid-4 tj-brand-grid">
						<?php foreach ( $brands as $brand ) : ?>
							<a class="tj-brand tj-reveal" href="<?php echo esc_url( $brand[2] ); ?>">
								<span class="tj-brand__mark"><?php echo esc_html( substr( $brand[0], 0, 1 ) ); ?></span>
								<span class="tj-brand__name"><?php echo esc_html( $brand[0] ); ?></span>
								<span class="tj-brand__desc"><?php echo esc_html( $brand[1] ); ?></span>
								<span class="tj-brand__arrow" aria-hidden="true">
									<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
								</span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
			<?php
		}
	);
}

/* =========================================================================
 * 5. SERVICES — Managed Network, Maintenance, License Renewal, Pengadaan
 * ====================================================================== */
function trijaya_render_services(): void {
	trijaya_section_render(
		'services',
		static function (): void {
			$services = array(
				array( __( 'Managed Network', 'trijaya-firewall' ), __( 'Managed firewall, WiFi & network monitoring 24/7 oleh tim engineer bersertifikat.', 'trijaya-firewall' ), home_url( '/services/managed-it-services/' ) ),
				array( __( 'Maintenance', 'trijaya-firewall' ), __( 'Maintenance firewall, switch, router, wireless & server dengan SLA yang jelas.', 'trijaya-firewall' ), home_url( '/services/it-maintenance/' ) ),
				array( __( 'License Renewal', 'trijaya-firewall' ), __( 'Renewal license Fortinet, FortiGate, Palo Alto, Sophos & security subscription.', 'trijaya-firewall' ), home_url( '/license-renewal/' ) ),
				array( __( 'Pengadaan', 'trijaya-firewall' ), __( 'Pengadaan firewall, switch, router, access point & appliance resmi bergaransi.', 'trijaya-firewall' ), home_url( '/products/' ) ),
			);
			?>
			<section id="services" class="tj-section">
				<div class="tj-container">
					<?php trijaya_section_head(
						__( 'Services', 'trijaya-firewall' ),
						__( 'Managed Service & Support Penuh', 'trijaya-firewall' )
					); ?>
					<div class="tj-grid tj-grid-4">
						<?php foreach ( $services as $i => $service ) : ?>
							<a href="<?php echo esc_url( $service[2] ); ?>" class="tj-service tj-reveal" style="--tj-delay:<?php echo esc_attr( (string) ( $i * 0.08 ) ); ?>s">
								<span class="tj-service__num">0<?php echo esc_html( (string) ( $i + 1 ) ); ?></span>
								<h3 class="tj-service__title"><?php echo esc_html( $service[0] ); ?></h3>
								<p class="tj-service__desc"><?php echo esc_html( $service[1] ); ?></p>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
			<?php
		}
	);
}

/* =========================================================================
 * 6. WHY US + STATS (counter animasi)
 * ====================================================================== */
function trijaya_render_why(): void {
	trijaya_section_render(
		'why',
		static function (): void {
			$stats = array(
				array( '500', '+', __( 'Project Selesai', 'trijaya-firewall' ) ),
				array( '150', '+', __( 'Klien Perusahaan', 'trijaya-firewall' ) ),
				array( '10', '+', __( 'Tahun Pengalaman', 'trijaya-firewall' ) ),
				array( '24', '/7', __( 'Support Monitoring', 'trijaya-firewall' ) ),
			);
			?>
			<section id="why" class="tj-section tj-section--alt tj-why">
				<div class="tj-container">
					<div class="tj-why__grid">
						<div class="tj-why__content tj-reveal">
							<?php trijaya_section_head(
								__( 'Kenapa Trijaya Firewall', 'trijaya-firewall' ),
								__( 'Partner Network Security yang Bisa Diandalkan', 'trijaya-firewall' )
							); ?>
							<ul class="tj-why__list">
								<li><strong><?php esc_html_e( 'Engineer Bersertifikat', 'trijaya-firewall' ); ?></strong><?php esc_html_e( ' — tim NSE/Fortinet, PCNSE, dan CCNA dengan pengalaman enterprise.', 'trijaya-firewall' ); ?></li>
								<li><strong><?php esc_html_e( 'Respons Cepat 24/7', 'trijaya-firewall' ); ?></strong><?php esc_html_e( ' — remote & onsite support dengan SLA yang jelas.', 'trijaya-firewall' ); ?></li>
								<li><strong><?php esc_html_e( 'Vendor Resmi', 'trijaya-firewall' ); ?></strong><?php esc_html_e( ' — lisensi & perangkat original bergaransi resmi.', 'trijaya-firewall' ); ?></li>
								<li><strong><?php esc_html_e( 'Coverage Jabodetabek & Luar Kota', 'trijaya-firewall' ); ?></strong><?php esc_html_e( ' — melayani seluruh Indonesia.', 'trijaya-firewall' ); ?></li>
							</ul>
							<a class="tj-btn tj-btn-primary" href="#kontak"><?php esc_html_e( 'Diskusikan Kebutuhan Anda', 'trijaya-firewall' ); ?></a>
						</div>
						<div class="tj-why__stats">
							<?php foreach ( $stats as $stat ) : ?>
								<div class="tj-stat tj-reveal">
									<span class="tj-stat__num"><span class="tj-counter" data-target="<?php echo esc_attr( $stat[0] ); ?>">0</span><?php echo esc_html( $stat[1] ); ?></span>
									<span class="tj-stat__label"><?php echo esc_html( $stat[2] ); ?></span>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</section>
			<?php
		}
	);
}

/* =========================================================================
 * 7. PORTFOLIO
 * ====================================================================== */
function trijaya_render_portfolio(): void {
	trijaya_section_render(
		'portfolio',
		static function (): void {
			$portfolio_page = get_page_by_path( 'portfolio' );
			$projects = $portfolio_page ? get_children( array( 'post_parent' => $portfolio_page->ID, 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 6 ) ) : array();
			?>
			<section id="portfolio" class="tj-section">
				<div class="tj-container">
					<?php trijaya_section_head(
						__( 'Portfolio', 'trijaya-firewall' ),
						__( 'Project yang Telah Kami Kerjakan', 'trijaya-firewall' ),
						__( 'Instalasi firewall, network security & infrastruktur jaringan untuk berbagai industri.', 'trijaya-firewall' )
					); ?>

					<?php if ( $projects ) : ?>
						<div class="tj-grid tj-grid-3">
							<?php foreach ( $projects as $project ) : ?>
								<article class="tj-project tj-reveal">
									<?php if ( has_post_thumbnail( $project ) ) : ?>
										<a class="tj-project__thumb" href="<?php echo esc_url( get_permalink( $project ) ); ?>">
											<?php echo get_the_post_thumbnail( $project, 'trijaya-card' ); ?>
										</a>
									<?php endif; ?>
									<div class="tj-project__body">
										<h3 class="tj-project__title"><a href="<?php echo esc_url( get_permalink( $project ) ); ?>"><?php echo esc_html( get_the_title( $project ) ); ?></a></h3>
										<a class="tj-card__more" href="<?php echo esc_url( get_permalink( $project ) ); ?>"><?php esc_html_e( 'Lihat project', 'trijaya-firewall' ); ?></a>
									</div>
								</article>
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<div class="tj-grid tj-grid-3 tj-cards">
							<?php
							$placeholders = array(
								__( 'Instalasi FortiGate untuk Perusahaan di Jakarta', 'trijaya-firewall' ),
								__( 'Site-to-Site VPN antar Kantor Cabang', 'trijaya-firewall' ),
								__( 'Deploy WiFi Enterprise Ruijie', 'trijaya-firewall' ),
								__( 'Network Audit Perusahaan Manufaktur', 'trijaya-firewall' ),
								__( 'Fiber Optic Data Center', 'trijaya-firewall' ),
								__( 'Renewal License Palo Alto', 'trijaya-firewall' ),
							);
							foreach ( $placeholders as $i => $title ) : ?>
								<article class="tj-card tj-card--project tj-reveal" style="--tj-delay:<?php echo esc_attr( (string) ( $i * 0.06 ) ); ?>s">
									<span class="tj-card__icon tj-card__icon--sm">
										<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="6" rx="2"/><rect x="3" y="14" width="18" height="6" rx="2"/><path d="M7 7h.01M7 17h.01"/></svg>
									</span>
									<h3 class="tj-card__title"><?php echo esc_html( $title ); ?></h3>
								</article>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</section>
			<?php
		}
	);
}

/* =========================================================================
 * 8. INSIGHT / BLOG
 * ====================================================================== */
function trijaya_render_insight(): void {
	trijaya_section_render(
		'insight',
		static function (): void {
			$posts_query = new WP_Query(
				array(
					'post_type'           => 'post',
					'posts_per_page'      => 3,
					'ignore_sticky_posts' => true,
				)
			);
			?>
			<section id="insight" class="tj-section tj-section--alt">
				<div class="tj-container">
					<?php trijaya_section_head(
						__( 'Insight', 'trijaya-firewall' ),
						__( 'Artikel & Tutorial Terbaru', 'trijaya-firewall' )
					); ?>
					<?php if ( $posts_query->have_posts() ) : ?>
						<div class="tj-grid tj-grid-3">
							<?php
							while ( $posts_query->have_posts() ) :
								$posts_query->the_post();
								?>
								<article id="post-<?php the_ID(); ?>" <?php post_class( 'tj-post tj-reveal' ); ?>>
									<?php if ( has_post_thumbnail() ) : ?>
										<a class="tj-post__thumb" href="<?php the_permalink(); ?>">
											<?php the_post_thumbnail( 'trijaya-card' ); ?>
										</a>
									<?php endif; ?>
									<div class="tj-post__body">
										<span class="tj-post__meta"><?php echo esc_html( get_the_date() ); ?></span>
										<h3 class="tj-post__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
										<p class="tj-post__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
									</div>
								</article>
								<?php
							endwhile;
							wp_reset_postdata();
							?>
						</div>
						<p class="tj-center">
							<a class="tj-btn tj-btn-primary" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : home_url( '/?post_type=post' ) ); ?>">
								<?php esc_html_e( 'Lihat Semua Insight', 'trijaya-firewall' ); ?>
							</a>
						</p>
					<?php else : ?>
						<p class="tj-center tj-muted"><?php esc_html_e( 'Belum ada artikel. Publikasikan insight pertama Anda!', 'trijaya-firewall' ); ?></p>
					<?php endif; ?>
				</div>
			</section>
			<?php
		}
	);
}

/* =========================================================================
 * 9. FAQ — pertanyaan SEO dari Workflow
 * ====================================================================== */
function trijaya_render_faq(): void {
	trijaya_section_render(
		'faq',
		static function (): void {
			$faqs = array(
				array( __( 'Berapa biaya instalasi firewall?', 'trijaya-firewall' ), __( 'Biaya tergantung tipe perangkat (FortiGate 40F/60F/80F dan seterusnya), kompleksitas topologi, dan kebutuhan policy. Hubungi kami untuk penawaran gratis sesuai kebutuhan Anda.', 'trijaya-firewall' ) ),
				array( __( 'Apa fungsi firewall dan apakah wajib untuk perusahaan?', 'trijaya-firewall' ), __( 'Firewall melindungi jaringan dari akses tidak sah, malware, dan ancaman eksternal. Untuk perusahaan yang terhubung internet, firewall adalah fondasi keamanan yang sangat disarankan.', 'trijaya-firewall' ) ),
				array( __( 'Apa perbedaan firewall dan router?', 'trijaya-firewall' ), __( 'Router menghubungkan jaringan dan mengatur lalu lintas data, sedangkan firewall menyaring dan memblokir lalu lintas berbahaya berdasarkan kebijakan keamanan. Perangkat NGFW modern umumnya menggabungkan keduanya.', 'trijaya-firewall' ) ),
				array( __( 'Apa itu FortiGate?', 'trijaya-firewall' ), __( 'FortiGate adalah seri firewall Fortinet berbasis Next-Generation Firewall (NGFW) dengan fitur IPS, antivirus, web filtering, VPN, dan SD-WAN dalam satu perangkat.', 'trijaya-firewall' ) ),
				array( __( 'Berapa biaya maintenance firewall?', 'trijaya-firewall' ), __( 'Biaya maintenance disesuaikan dengan tipe perangkat dan level SLA (respons remote atau onsite 24/7). Kami menyediakan paket maintenance bulanan dan tahunan.', 'trijaya-firewall' ) ),
				array( __( 'Bagaimana cara renewal license Fortinet / FortiGate?', 'trijaya-firewall' ), __( 'Cukup kirimkan seri perangkat dan nomor kontrak FortiCare/FortiGuard kepada kami — tim kami akan memproses renewal dan aktivasi license hingga selesai.', 'trijaya-firewall' ) ),
				array( __( 'Apa itu VPN dan perbedaan site-to-site dengan remote access VPN?', 'trijaya-firewall' ), __( 'VPN menghubungkan jaringan melalui terowongan terenkripsi. Site-to-Site VPN menghubungkan dua kantor/jaringan secara permanen, sementara remote access VPN mengizinkan karyawan mengakses jaringan kantor dari mana saja.', 'trijaya-firewall' ) ),
			);
			?>
			<section id="faq" class="tj-section">
				<div class="tj-container tj-container--narrow">
					<?php trijaya_section_head(
						__( 'FAQ', 'trijaya-firewall' ),
						__( 'Pertanyaan yang Sering Diajukan', 'trijaya-firewall' )
					); ?>
					<div class="tj-faq">
						<?php foreach ( $faqs as $i => $faq ) : ?>
							<details class="tj-faq__item tj-reveal"<?php echo 0 === $i ? ' open' : ''; ?>>
								<summary class="tj-faq__q">
									<span><?php echo esc_html( $faq[0] ); ?></span>
									<span class="tj-faq__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
								</summary>
								<div class="tj-faq__a"><?php echo esc_html( $faq[1] ); ?></div>
							</details>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
			<?php
		}
	);
}

/* =========================================================================
 * 10. KONTAK / CTA
 * ====================================================================== */
function trijaya_render_kontak(): void {
	trijaya_section_render(
		'kontak',
		static function (): void {
			$phone   = get_theme_mod( 'trijaya_contact_phone', '' );
			$email   = get_theme_mod( 'trijaya_contact_email', '' );
			$address = get_theme_mod( 'trijaya_contact_address', '' );
			?>
			<section id="kontak" class="tj-section tj-cta">
				<div class="tj-container">
					<div class="tj-cta__box tj-reveal">
						<div class="tj-cta__deco" aria-hidden="true"></div>
						<div class="tj-cta__content">
							<h2 class="tj-cta__title"><?php esc_html_e( 'Siap Mengamankan Jaringan Perusahaan Anda?', 'trijaya-firewall' ); ?></h2>
							<p class="tj-cta__desc"><?php esc_html_e( 'Konsultasikan kebutuhan firewall, network security, dan cyber security Anda dengan engineer kami — gratis, tanpa komitmen.', 'trijaya-firewall' ); ?></p>
							<div class="tj-cta__actions">
								<a class="tj-btn tj-btn-light tj-btn-lg" href="<?php echo esc_url( trijaya_whatsapp_link() ); ?>" target="_blank" rel="noopener">
									<svg viewBox="0 0 24 24" width="19" height="19" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5.1-1.3A10 10 0 1 0 12 2zm5.3 14.1c-.2.6-1.2 1.2-1.7 1.2-.4.1-1 .1-1.6-.1-.4-.1-.9-.3-1.5-.5-2.6-1.1-4.3-3.8-4.5-4-.1-.2-1-1.4-1-2.6s.6-1.8.9-2c.2-.3.5-.3.7-.3h.5c.2 0 .4-.1.6.4.2.6.8 2 .8 2.1.1.1.1.3 0 .5l-.3.4c-.1.2-.3.3-.1.6.2.3.8 1.2 1.6 2 1.1.9 1.9 1.2 2.2 1.3.3.1.5.1.6-.1l.9-1c.2-.3.4-.2.6-.1l2 1c.3.1.5.2.5.3.1.2.1.7-.1 1.3z"/></svg>
									<?php esc_html_e( 'Chat WhatsApp', 'trijaya-firewall' ); ?>
								</a>
								<a class="tj-btn tj-btn-outline tj-btn-lg" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Halaman Kontak', 'trijaya-firewall' ); ?></a>
							</div>
							<ul class="tj-cta__info">
								<?php if ( $phone ) : ?><li>📞 <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li><?php endif; ?>
								<?php if ( $email ) : ?><li>✉️ <a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a></li><?php endif; ?>
								<?php if ( $address ) : ?><li>📍 <?php echo esc_html( $address ); ?></li><?php endif; ?>
							</ul>
						</div>
					</div>
				</div>
			</section>
			<?php
		}
	);
}
