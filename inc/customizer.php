<?php
/**
 * Customizer: identitas, hero slider, warna, kontak, chat AI.
 *
 * @package Trijaya_Firewall
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sanitize checkbox.
 */
function trijaya_sanitize_checkbox( mixed $checked ): bool {
	return ( isset( $checked ) && true === (bool) $checked );
}

/**
 * Register panel & sections.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function trijaya_customize_register( WP_Customize_Manager $wp_customize ): void {

	/* ============================ HERO SLIDER ============================ */
	$wp_customize->add_section(
		'trijaya_hero',
		array(
			'title'       => __( '⭐ Hero / Slider Homepage', 'trijaya-firewall' ),
			'description' => __( 'Isi 1 slide = hero statis dengan animasi. Isi lebih dari 1 slide = otomatis menjadi slider.', 'trijaya-firewall' ),
			'priority'    => 25,
		)
	);

	for ( $i = 1; $i <= 5; $i++ ) {
		/* translators: %d: slide number. */
		$label = sprintf( __( 'Slide %d', 'trijaya-firewall' ), $i );

		$wp_customize->add_setting( "trijaya_hero_{$i}_use", array(
			'default'           => $i <= 3,
			'sanitize_callback' => 'trijaya_sanitize_checkbox',
		) );
		$wp_customize->add_control( "trijaya_hero_{$i}_use", array(
			/* translators: %d: slide number. */
			'label'   => sprintf( __( 'Aktifkan Slide %d', 'trijaya-firewall' ), $i ),
			'section' => 'trijaya_hero',
			'type'    => 'checkbox',
		) );

		$fields = array(
			'title'    => array( $label . ' — Judul', 'sanitize_text_field', 1 === $i ? 'Jasa Firewall & Network Security Indonesia' : '' ),
			'subtitle' => array( $label . ' — Subjudul', 'sanitize_textarea_field', '' ),
			'btn1_txt' => array( $label . ' — Tombol 1 (teks)', 'sanitize_text_field', __( 'Konsultasi Gratis', 'trijaya-firewall' ) ),
			'btn1_url' => array( $label . ' — Tombol 1 (URL)', 'esc_url_raw', '#kontak' ),
			'btn2_txt' => array( $label . ' — Tombol 2 (teks)', 'sanitize_text_field', __( 'Lihat Layanan', 'trijaya-firewall' ) ),
			'btn2_url' => array( $label . ' — Tombol 2 (URL)', 'esc_url_raw', '#layanan' ),
			'image'    => array( $label . ' — Gambar (URL)', 'esc_url_raw', '' ),
		);

		foreach ( $fields as $key => $conf ) {
			$wp_customize->add_setting( "trijaya_hero_{$i}_{$key}", array(
				'default'           => $conf[2],
				'sanitize_callback' => $conf[1],
			) );
			$wp_customize->add_control( "trijaya_hero_{$i}_{$key}", array(
				'label'   => $conf[0],
				'section' => 'trijaya_hero',
				'type'    => 'image' === $key ? 'url' : ( 'subtitle' === $key ? 'textarea' : 'text' ),
			) );
		}
	}

	/* ============================ WARNA ============================ */
	$wp_customize->add_section(
		'trijaya_colors',
		array(
			'title'    => __( '🎨 Warna Theme', 'trijaya-firewall' ),
			'priority' => 30,
		)
	);

	$colors = array(
		'trijaya_color_accent'   => array( __( 'Warna Aksen Utama', 'trijaya-firewall' ), '#1d6ff2' ),
		'trijaya_color_accent_2' => array( __( 'Warna Aksen Kedua (gradient)', 'trijaya-firewall' ), '#00b4d8' ),
	);

	foreach ( $colors as $id => $conf ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $conf[1],
			'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array(
			'label'   => $conf[0],
			'section' => 'trijaya_colors',
		) ) );
	}

	/* ============================ KONTAK ============================ */
	$wp_customize->add_section(
		'trijaya_contact',
		array(
			'title'    => __( '📞 Info Kontak & Footer', 'trijaya-firewall' ),
			'priority' => 35,
		)
	);

	$contacts = array(
		'trijaya_contact_phone'   => array( __( 'Nomor Telepon', 'trijaya-firewall' ), '' ),
		'trijaya_contact_wa'      => array( __( 'Nomor WhatsApp (format 62xxx)', 'trijaya-firewall' ), '6281234567890' ),
		'trijaya_contact_email'   => array( __( 'Email', 'trijaya-firewall' ), '' ),
		'trijaya_contact_address' => array( __( 'Alamat', 'trijaya-firewall' ), '' ),
		'trijaya_footer_text'     => array( __( 'Teks Deskripsi Footer', 'trijaya-firewall' ), __( 'Spesialis Firewall, Network Security & Cyber Security untuk bisnis dan enterprise di Indonesia.', 'trijaya-firewall' ) ),
	);

	foreach ( $contacts as $id => $conf ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $conf[1],
			'sanitize_callback' => 'trijaya_contact_address' === $id || 'trijaya_footer_text' === $id ? 'sanitize_textarea_field' : 'sanitize_text_field',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $conf[0],
			'section' => 'trijaya_contact',
			'type'    => in_array( $id, array( 'trijaya_contact_address', 'trijaya_footer_text' ), true ) ? 'textarea' : 'text',
		) );
	}

	/* ============================ CHAT AI (GROQ) ============================ */
	$wp_customize->add_section(
		'trijaya_chat',
		array(
			'title'       => __( '🤖 AI Chat (Groq / Grok)', 'trijaya-firewall' ),
			'description' => __( 'Widget chat AI di front-end. Default menggunakan Groq API (cepat & murah). Endpoint juga bisa diganti ke xAI Grok atau provider lain yang kompatibel OpenAI.', 'trijaya-firewall' ),
			'priority'    => 40,
		)
	);

	$chat = array(
		'trijaya_chat_enable'    => array( __( 'Aktifkan AI Chat', 'trijaya-firewall' ), true, 'checkbox', 'trijaya_sanitize_checkbox' ),
		'trijaya_chat_api_key'   => array( __( 'API Key (Groq: console.groq.com)', 'trijaya-firewall' ), '', 'password', 'sanitize_text_field' ),
		'trijaya_chat_endpoint'  => array( __( 'Endpoint API', 'trijaya-firewall' ), 'https://api.groq.com/openai/v1/chat/completions', 'url', 'esc_url_raw' ),
		'trijaya_chat_model'     => array( __( 'Model', 'trijaya-firewall' ), 'llama-3.3-70b-versatile', 'text', 'sanitize_text_field' ),
		'trijaya_chat_name'      => array( __( 'Nama Asisten', 'trijaya-firewall' ), __( 'TJ Assistant', 'trijaya-firewall' ), 'text', 'sanitize_text_field' ),
		'trijaya_chat_welcome'   => array( __( 'Pesan Sambutan', 'trijaya-firewall' ), __( 'Hai! 👋 Saya asisten AI Trijaya Firewall. Ada yang bisa dibantu seputar firewall, network security, atau renewal license?', 'trijaya-firewall' ), 'textarea', 'sanitize_textarea_field' ),
		'trijaya_chat_system'    => array( __( 'System Prompt (instruksi AI)', 'trijaya-firewall' ), 'You are a helpful assistant for Trijaya Firewall, an Indonesian network security company. Answer briefly in Bahasa Indonesia. Services: firewall installation, configuration, maintenance, license renewal, network security, cyber security, managed network.', 'textarea', 'sanitize_textarea_field' ),
		'trijaya_chat_quick1'    => array( __( 'Quick Reply 1', 'trijaya-firewall' ), __( 'Harga instalasi firewall?', 'trijaya-firewall' ), 'text', 'sanitize_text_field' ),
		'trijaya_chat_quick2'    => array( __( 'Quick Reply 2', 'trijaya-firewall' ), __( 'Renewal license FortiGate', 'trijaya-firewall' ), 'text', 'sanitize_text_field' ),
		'trijaya_chat_quick3'    => array( __( 'Quick Reply 3', 'trijaya-firewall' ), __( 'Jasa pentest / audit', 'trijaya-firewall' ), 'text', 'sanitize_text_field' ),
	);

	foreach ( $chat as $id => $conf ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $conf[1],
			'sanitize_callback' => $conf[3],
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $conf[0],
			'section' => 'trijaya_chat',
			'type'    => $conf[2],
		) );
	}
}
add_action( 'customize_register', 'trijaya_customize_register' );

/**
 * Live preview JS (selective refresh tidak wajib, ini opsional).
 */
function trijaya_customize_preview_js(): void {
	wp_enqueue_script( 'trijaya-customizer', TRIJAYA_URI . '/assets/js/customizer.js', array( 'customize-preview' ), TRIJAYA_VERSION, true );
}
add_action( 'customize_preview_init', 'trijaya_customize_preview_js' );
