<?php
/**
 * Trijaya Firewall — dukungan PWA (Progressive Web App).
 *
 * Manifest, service worker, halaman offline, dan meta install-to-homescreen.
 * Semua endpoint disajikan dinamis dari root domain (/?tj_pwa=…) agar
 * scope service worker tetap "/" sehingga seluruh situs bisa di-install
 * sebagai aplikasi & berjalan offline-capable.
 *
 * @package Trijaya_Firewall
 */

defined( 'ABSPATH' ) || exit;

/**
 * PWA aktif atau tidak (bisa dimatikan via filter).
 */
function trijaya_pwa_enabled(): bool {
	return (bool) apply_filters( 'trijaya_pwa_enable', true );
}

/**
 * URL endpoint PWA di root domain (aman untuk scope SW = "/").
 *
 * @param string $mode manifest|sw|offline.
 */
function trijaya_pwa_url( string $mode ): string {
	return add_query_arg( 'tj_pwa', $mode, home_url( '/' ) );
}

/**
 * Warna aksen aktif dari Customizer (fallback biru brand).
 */
function trijaya_pwa_accent(): string {
	$accent = sanitize_hex_color( get_theme_mod( 'trijaya_color_accent', '#1d6ff2' ) );
	return $accent ? $accent : '#1d6ff2';
}

/**
 * Nama pendek aplikasi untuk homescreen.
 */
function trijaya_pwa_short_name(): string {
	$name = wp_strip_all_tags( get_bloginfo( 'name' ) );
	if ( '' === $name ) {
		$name = 'Trijaya Firewall';
	}
	return wp_trim_words( $name, 2, '' );
}

/**
 * Daftar ikon PWA — otomatis memakai Site Icon bila diset,
 * jika tidak memakai ikon bawaan theme (any + maskable).
 */
function trijaya_pwa_icons(): array {
	$theme_uri = get_template_directory_uri() . '/assets/img/';
	$icons     = array(
		array(
			'src'     => $theme_uri . 'icon-192.png',
			'sizes'   => '192x192',
			'type'    => 'image/png',
			'purpose' => 'any',
		),
		array(
			'src'     => $theme_uri . 'icon-512.png',
			'sizes'   => '512x512',
			'type'    => 'image/png',
			'purpose' => 'any',
		),
		array(
			'src'     => $theme_uri . 'icon-maskable-512.png',
			'sizes'   => '512x512',
			'type'    => 'image/png',
			'purpose' => 'maskable',
		),
	);

	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
		$site  = get_site_icon_url();
		$icons = array(
			array(
				'src'     => $site,
				'sizes'   => '192x192',
				'type'    => 'image/png',
				'purpose' => 'any',
			),
			array(
				'src'     => $site,
				'sizes'   => '512x512',
				'type'    => 'image/png',
				'purpose' => 'any',
			),
			array(
				'src'     => $site,
				'sizes'   => '512x512',
				'type'    => 'image/png',
				'purpose' => 'maskable',
			),
		);
	}

	return $icons;
}

/**
 * Data lengkap web app manifest.
 */
function trijaya_pwa_manifest(): array {
	$accent    = trijaya_pwa_accent();
	$name      = wp_strip_all_tags( get_bloginfo( 'name' ) );
	$desc      = wp_strip_all_tags( get_bloginfo( 'description' ) );
	$manifest  = array(
		'id'               => home_url( '/' ),
		'name'             => $name ? $name : 'Trijaya Firewall',
		'short_name'       => trijaya_pwa_short_name(),
		'description'      => $desc ? $desc : __( 'Spesialis Firewall & Network Security Indonesia', 'trijaya-firewall' ),
		'url'              => home_url( '/' ),
		'start_url'        => add_query_arg(
			array(
				'utm_source'  => 'pwa',
				'utm_medium'  => 'homescreen',
			),
			home_url( '/' )
		),
		'scope'            => '/',
		'display'          => 'standalone',
		'background_color' => '#ffffff',
		'theme_color'      => $accent,
		'lang'             => get_bloginfo( 'language' ),
		'dir'              => 'ltr',
		'categories'       => array( 'business', 'productivity', 'technology' ),
		'icons'            => trijaya_pwa_icons(),
	);

	// Shortcut "Konsultasi" ke WhatsApp bila nomor terisi.
	if ( function_exists( 'trijaya_whatsapp_link' ) ) {
		$manifest['shortcuts'] = array(
			array(
				'name'  => __( 'Konsultasi Gratis', 'trijaya-firewall' ),
				'url'   => trijaya_whatsapp_link(),
				'icons' => array(
					array(
						'src'   => get_template_directory_uri() . '/assets/img/icon-192.png',
						'sizes' => '192x192',
					),
				),
			),
		);
	}

	return apply_filters( 'trijaya_pwa_manifest', $manifest );
}

/* -------------------------------------------------------------------------
 * Server endpoint: ?tj_pwa=manifest|sw|offline  (dari init, sebelum render)
 * ---------------------------------------------------------------------- */
function trijaya_pwa_maybe_serve(): void {
	if ( ! trijaya_pwa_enabled() || empty( $_GET['tj_pwa'] ) ) {
		return;
	}

	$mode = sanitize_key( wp_unslash( $_GET['tj_pwa'] ) );

	switch ( $mode ) {
		case 'manifest':
			trijaya_pwa_serve_manifest();
			break;
		case 'sw':
			trijaya_pwa_serve_sw();
			break;
		case 'offline':
			trijaya_pwa_serve_offline();
			break;
	}
}
add_action( 'init', 'trijaya_pwa_maybe_serve', 1 );

/** Kirim web app manifest sebagai JSON. */
function trijaya_pwa_serve_manifest(): void {
	nocache_headers();
	header( 'Content-Type: application/manifest+json; charset=utf-8' );
	echo wp_json_encode( trijaya_pwa_manifest(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	exit;
}

/** Kirim service worker dari root domain. */
function trijaya_pwa_serve_sw(): void {
	nocache_headers();
	header( 'Content-Type: application/javascript; charset=utf-8' );
	header( 'Service-Worker-Allowed: /' );

	$version = defined( 'TRIJAYA_VERSION' ) ? TRIJAYA_VERSION : '1.2.0';
	$offline = wp_json_encode( trijaya_pwa_url( 'offline' ) );

	$sw = <<<JS
/* Trijaya Firewall — Service Worker v{$version} */
const VERSION = 'trijaya-{$version}';
const OFFLINE_URL = {$offline};
const PRECACHE = [OFFLINE_URL];
const FONTS = /(^|\\.)fonts\\.(googleapis|gstatic)\\.com$/;

self.addEventListener('install', (e) => {
	e.waitUntil(
		caches.open(VERSION)
			.then((c) => c.addAll(PRECACHE))
			.then(() => self.skipWaiting())
			.catch(() => {})
	);
});

self.addEventListener('activate', (e) => {
	e.waitUntil(
		caches.keys()
			.then((keys) => Promise.all(keys.filter((k) => k.indexOf('trijaya-') === 0 && k !== VERSION).map((k) => caches.delete(k))))
			.then(() => self.clients.claim())
			.catch(() => {})
	);
});

self.addEventListener('fetch', (e) => {
	const req = e.request;
	if (req.method !== 'GET') { return; }

	const url = new URL(req.url);
	const sameOrigin = url.origin === self.location.origin;

	// Hanya aset situs sendiri + Google Fonts.
	if (!sameOrigin && !FONTS.test(url.hostname)) { return; }
	// Area admin, REST API & endpoint PWA itu sendiri tidak di-cache.
	if (sameOrigin && (url.pathname.indexOf('/wp-admin') === 0 || url.pathname.indexOf('/wp-login') === 0 || url.pathname.indexOf('/wp-json') === 0 || url.search.indexOf('tj_pwa=') !== -1)) { return; }

	// Navigasi halaman: network-first → cache → halaman offline.
	if (req.mode === 'navigate') {
		e.respondWith(
			fetch(req).then((res) => {
				const copy = res.clone();
				caches.open(VERSION).then((c) => c.put(req, copy));
				return res;
			}).catch(() => caches.match(req).then((hit) => hit || caches.match(OFFLINE_URL)))
		);
		return;
	}

	// Aset statis (CSS/JS/gambar/font).
	const saveCopy = (res) => {
		if (res && (res.ok || res.type === 'opaque')) {
			const copy = res.clone();
			caches.open(VERSION).then((c) => c.put(req, copy));
		}
		return res;
	};

	// CSS & JS: NETWORK-FIRST — perubahan tema langsung tayang di HP,
	// cache hanya jadi cadangan saat offline. (Dulu stale-while-revalidate
	// sehingga HP selalu menampilkan file LAMA dulu walau server sudah update.)
	if (sameOrigin && /\.(css|js)(\?|$)/i.test(url.pathname)) {
		e.respondWith(
			fetch(req).then(saveCopy).catch(() => caches.match(req))
		);
		return;
	}

	// Gambar & font: stale-while-revalidate (oks, tidak sering berubah).
	e.respondWith(
		caches.match(req).then((hit) => {
			const network = fetch(req).then(saveCopy).catch(() => hit);
			return hit || network;
		})
	);
});
JS;

	echo $sw;
	exit;
}

/** Halaman offline ringan (di-precache oleh service worker). */
function trijaya_pwa_serve_offline(): void {
	$accent = trijaya_pwa_accent();
	$name   = trijaya_pwa_short_name();
	$home   = esc_url( home_url( '/' ) );

	header( 'Content-Type: text/html; charset=utf-8' );
	header( 'Cache-Control: no-cache' );

	echo '<!doctype html><html lang="' . esc_attr( get_bloginfo( 'language' ) ) . '"><head>';
	echo '<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
	echo '<title>' . esc_html__( 'Anda sedang offline', 'trijaya-firewall' ) . '</title>';
	echo '<style>';
	echo 'body{margin:0;min-height:100vh;display:grid;place-items:center;text-align:center;font-family:Inter,system-ui,-apple-system,sans-serif;background:#f5f8fd;color:#0d1b33;padding:24px;}';
	echo '.box{max-width:420px;}svg{width:84px;height:84px;margin-bottom:18px;}h1{font-size:1.5rem;margin:0 0 10px;}p{color:#47536b;margin:0 0 26px;line-height:1.6;}';
	echo 'a.btn{display:inline-flex;padding:14px 30px;border-radius:999px;background:linear-gradient(135deg,' . esc_html( $accent ) . ',#00b4d8);color:#fff;text-decoration:none;font-weight:600;}';
	echo '</style></head><body><div class="box">';
	echo '<svg viewBox="0 0 24 24" fill="none" stroke="' . esc_html( $accent ) . '" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-3.6 8-10V5l-8-3-8 3v7c0 6.4 8 10 8 10z"/><path d="M9.5 12l1.8 1.8 3.4-3.6"/></svg>';
	echo '<h1>' . esc_html__( 'Anda sedang offline', 'trijaya-firewall' ) . '</h1>';
	/* translators: %s: nama situs. */
	echo '<p>' . esc_html( sprintf( __( 'Koneksi internet tidak tersedia. Buka kembali %s saat Anda kembali online.', 'trijaya-firewall' ), $name ) ) . '</p>';
	echo '<a class="btn" href="' . $home . '">' . esc_html__( 'Coba Lagi', 'trijaya-firewall' ) . '</a>';
	echo '</div></body></html>';
	exit;
}

/* -------------------------------------------------------------------------
 * Meta <head>: manifest, theme-color, apple-touch-icon, favicon fallback
 * ---------------------------------------------------------------------- */
function trijaya_pwa_head(): void {
	if ( ! trijaya_pwa_enabled() ) {
		return;
	}

	$accent    = trijaya_pwa_accent();
	$has_icon  = function_exists( 'has_site_icon' ) && has_site_icon();
	$theme_img = get_template_directory_uri() . '/assets/img/';

	echo '<link rel="manifest" href="' . esc_url( trijaya_pwa_url( 'manifest' ) ) . '">' . "\n";
	echo '<meta name="theme-color" content="' . esc_attr( $accent ) . '">' . "\n";
	echo '<meta name="mobile-web-app-capable" content="yes">' . "\n";
	echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
	echo '<meta name="apple-mobile-web-app-status-bar-style" content="default">' . "\n";
	echo '<meta name="apple-mobile-web-app-title" content="' . esc_attr( trijaya_pwa_short_name() ) . '">' . "\n";

	if ( ! $has_icon ) {
		echo '<link rel="apple-touch-icon" href="' . esc_url( $theme_img . 'apple-touch-icon.png' ) . '">' . "\n";

		// Favicon fallback: perisai SVG dengan warna aksen aktif.
		$shield = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="' . $accent . '" d="M12 2l8 3v6c0 5-3.4 9.3-8 11-4.6-1.7-8-6-8-11V5l8-3z"/></svg>';
		echo '<link rel="icon" href="data:image/svg+xml,' . rawurlencode( $shield ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'trijaya_pwa_head', 5 );

/* -------------------------------------------------------------------------
 * Registrasi service worker (setelah JS utama termuat)
 * ---------------------------------------------------------------------- */
function trijaya_pwa_register_assets(): void {
	if ( ! trijaya_pwa_enabled() || ! wp_script_is( 'trijaya-main', 'registered' ) ) {
		return;
	}

	$sw = wp_json_encode( add_query_arg( array( 'tj_pwa' => 'sw', 'v' => TRIJAYA_VERSION ), home_url( '/' ) ) );
	$js = "if('serviceWorker' in navigator){window.addEventListener('load',function(){navigator.serviceWorker.register({$sw},{scope:'/'}).catch(function(){});});}";

	wp_add_inline_script( 'trijaya-main', $js );
}
add_action( 'wp_enqueue_scripts', 'trijaya_pwa_register_assets', 20 );
