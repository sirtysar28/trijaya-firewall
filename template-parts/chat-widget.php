<?php
/**
 * Widget AI Chat (Groq) — front-end.
 *
 * @package Trijaya_Firewall
 */

$chat_name  = get_theme_mod( 'trijaya_chat_name', __( 'TJ Assistant', 'trijaya-firewall' ) );
$welcome    = get_theme_mod( 'trijaya_chat_welcome', __( 'Hai! 👋 Ada yang bisa dibantu seputar firewall & network security?', 'trijaya-firewall' ) );
$quicks     = array_filter( array(
	get_theme_mod( 'trijaya_chat_quick1', __( 'Harga instalasi firewall?', 'trijaya-firewall' ) ),
	get_theme_mod( 'trijaya_chat_quick2', __( 'Renewal license FortiGate', 'trijaya-firewall' ) ),
	get_theme_mod( 'trijaya_chat_quick3', __( 'Jasa pentest / audit', 'trijaya-firewall' ) ),
) );
?>
<div class="tj-chat" id="tj-chat" data-open="false">
	<div class="tj-chat__panel" id="tj-chat-panel" role="dialog" aria-label="<?php echo esc_attr( $chat_name ); ?>" aria-modal="false" hidden>
		<header class="tj-chat__head">
			<span class="tj-chat__avatar" aria-hidden="true">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="7" width="16" height="12" rx="3"/><path d="M12 7V4M9 4h6M9 12h.01M15 12h.01M9.5 15.5h5"/></svg>
			</span>
			<div class="tj-chat__headtxt">
				<strong><?php echo esc_html( $chat_name ); ?></strong>
				<span class="tj-chat__status"><i></i><?php esc_html_e( 'Online — AI Assistant', 'trijaya-firewall' ); ?></span>
			</div>
			<button type="button" class="tj-chat__close" aria-label="<?php esc_attr_e( 'Tutup chat', 'trijaya-firewall' ); ?>">&times;</button>
		</header>
		<div class="tj-chat__body" id="tj-chat-body" aria-live="polite">
			<div class="tj-chat__msg tj-chat__msg--bot"><p><?php echo esc_html( $welcome ); ?></p></div>
			<?php if ( $quicks ) : ?>
				<div class="tj-chat__quicks">
					<?php foreach ( $quicks as $quick ) : ?>
						<button type="button" class="tj-chat__quick"><?php echo esc_html( $quick ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<form class="tj-chat__form" id="tj-chat-form" autocomplete="off">
			<input type="text" class="tj-chat__input" id="tj-chat-input" placeholder="<?php esc_attr_e( 'Ketik pesan…', 'trijaya-firewall' ); ?>" maxlength="1000" required>
			<button type="submit" class="tj-chat__send" aria-label="<?php esc_attr_e( 'Kirim', 'trijaya-firewall' ); ?>">
				<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
			</button>
		</form>
		<footer class="tj-chat__foot">
			<a href="https://trijayasolution.co.id" target="_blank" rel="noopener nofollow"><?php esc_html_e( 'Powered by Trijaya Solution', 'trijaya-firewall' ); ?></a>
		</footer>
	</div>

	<button type="button" class="tj-chat__toggle" aria-expanded="false" aria-controls="tj-chat-panel" aria-label="<?php esc_attr_e( 'Buka AI Chat', 'trijaya-firewall' ); ?>">
		<svg class="tj-chat__icon-open" viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a8 8 0 0 1-8 8H4l2.5-2.5A8 8 0 1 1 21 12z"/><path d="M9 11h.01M13 11h.01"/></svg>
		<svg class="tj-chat__icon-close" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
		<span class="tj-chat__ping" aria-hidden="true"></span>
	</button>
</div>
