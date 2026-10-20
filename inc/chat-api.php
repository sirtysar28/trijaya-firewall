<?php
/**
 * REST API proxy untuk AI Chat (Groq / xAI Grok / OpenAI-compatible).
 * API key disimpan di server — tidak terekspos ke browser.
 *
 * @package Trijaya_Firewall
 */

defined( 'ABSPATH' ) || exit;

/**
 * Apakah chat AI sudah dikonfigurasi (API key terisi)?
 */
function trijaya_chat_configured(): bool {
	$key = trim( (string) get_theme_mod( 'trijaya_chat_api_key', '' ) );
	return '' !== $key;
}

/**
 * Register REST route: POST /wp-json/trijaya/v1/chat
 */
function trijaya_register_chat_route(): void {
	register_rest_route(
		'trijaya/v1',
		'/chat',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'trijaya_chat_handler',
			'permission_callback' => '__return_true',
			'args'                => array(
				'message' => array(
					'required'          => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_textarea_field',
					'validate_callback' => static fn( $param ) => is_string( $param ) && mb_strlen( trim( $param ) ) > 0 && mb_strlen( $param ) <= 2000,
				),
				'history' => array(
					'required'          => false,
					'type'              => 'array',
					'default'           => array(),
					'sanitize_callback' => static function ( $history ) {
						if ( ! is_array( $history ) ) {
							return array();
						}
						$clean = array();
						$round = 0;
						foreach ( $history as $item ) {
							if ( $round >= 12 ) {
								break; // batasi riwayat.
							}
							if ( is_array( $item ) && isset( $item['role'], $item['content'] ) && in_array( $item['role'], array( 'user', 'assistant' ), true ) ) {
								$clean[] = array(
									'role'    => $item['role'],
									'content' => mb_substr( sanitize_textarea_field( (string) $item['content'] ), 0, 2000 ),
								);
								$round++;
							}
						}
						return $clean;
					},
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'trijaya_register_chat_route' );

/**
 * Handler request chat.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function trijaya_chat_handler( WP_REST_Request $request ) {
	$api_key = trim( (string) get_theme_mod( 'trijaya_chat_api_key', '' ) );

	if ( '' === $api_key ) {
		return new WP_Error( 'trijaya_chat_no_key', __( 'AI Chat belum dikonfigurasi. Isi API Key di Customizer → AI Chat (Groq).', 'trijaya-firewall' ), array( 'status' => 503 ) );
	}

	$message = trim( (string) $request->get_param( 'message' ) );
	$history = (array) $request->get_param( 'history' );

	// Rate-limit ringan per IP: 20 pesan / 5 menit.
	$ip   = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$transient_key = 'tj_chat_' . md5( $ip );
	$hits = (int) get_transient( $transient_key );
	if ( $hits >= 20 ) {
		return new WP_Error( 'trijaya_chat_rate', __( 'Terlalu banyak pesan. Coba lagi beberapa menit lagi.', 'trijaya-firewall' ), array( 'status' => 429 ) );
	}
	set_transient( $transient_key, $hits + 1, 5 * MINUTE_IN_SECONDS );

	$messages = array(
		array(
			'role'    => 'system',
			'content' => (string) get_theme_mod( 'trijaya_chat_system', 'You are a helpful assistant. Answer briefly in Bahasa Indonesia.' ),
		),
	);

	foreach ( $history as $item ) {
		$messages[] = array(
			'role'    => (string) $item['role'],
			'content' => (string) $item['content'],
		);
	}

	$messages[] = array( 'role' => 'user', 'content' => $message );

	$endpoint = esc_url_raw( (string) get_theme_mod( 'trijaya_chat_endpoint', 'https://api.groq.com/openai/v1/chat/completions' ) );
	$model    = sanitize_text_field( (string) get_theme_mod( 'trijaya_chat_model', 'llama-3.3-70b-versatile' ) );

	$response = wp_remote_post(
		$endpoint,
		array(
			'timeout' => 30,
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $api_key,
			),
			'body'    => wp_json_encode(
				array(
					'model'      => $model,
					'messages'   => $messages,
					'temperature' => 0.6,
					'max_tokens' => 700,
				)
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return new WP_Error( 'trijaya_chat_error', __( 'Server AI sedang tidak dapat dihubungi. Silakan coba lagi.', 'trijaya-firewall' ), array( 'status' => 502 ) );
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	$body = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( $code < 200 || $code >= 300 || empty( $body['choices'][0]['message']['content'] ) ) {
		$detail = isset( $body['error']['message'] ) ? sanitize_text_field( (string) $body['error']['message'] ) : '';
		return new WP_Error(
			'trijaya_chat_upstream',
			$detail ? $detail : __( 'Terjadi kendala pada server AI. Coba lagi sebentar.', 'trijaya-firewall' ),
			array( 'status' => 502 )
		);
	}

	return rest_ensure_response(
		array(
			'reply' => wp_kses_post( trim( (string) $body['choices'][0]['message']['content'] ) ),
		)
	);
}
