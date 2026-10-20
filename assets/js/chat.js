/**
 * Trijaya Firewall v1.1.0 — AI Chat Widget (Groq)
 * Terhubung ke REST proxy theme (/wp-json/trijaya/v1/chat) sehingga
 * API key tetap aman di server.
 */
(function () {
	'use strict';

	var cfg = window.trijayaChat || {};

	function init() {
		var chat = document.getElementById('tj-chat');
		if (!chat) { return; }

		var toggle = chat.querySelector('.tj-chat__toggle');
		var panel = chat.querySelector('.tj-chat__panel');
		var closeBtn = chat.querySelector('.tj-chat__close');
		var body = chat.querySelector('.tj-chat__body');
		var form = chat.querySelector('.tj-chat__form');
		var input = chat.querySelector('.tj-chat__input');
		var sendBtn = chat.querySelector('.tj-chat__send');
		var history = [];
		var busy = false;

		function setPanel(open) {
			chat.setAttribute('data-open', open ? 'true' : 'false');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			toggle.setAttribute('aria-label', open ? 'Tutup AI Chat' : 'Buka AI Chat');
			if (open) {
				panel.hidden = false;
				body.scrollTop = body.scrollHeight;
				input.focus();
			}
		}

		toggle.addEventListener('click', function () {
			setPanel('true' !== chat.getAttribute('data-open'));
		});
		if (closeBtn) {
			closeBtn.addEventListener('click', function () { setPanel(false); });
		}
		document.addEventListener('keydown', function (e) {
			if ('Escape' === e.key) { setPanel(false); }
		});

		function addMsg(text, who) {
			var div = document.createElement('div');
			div.className = 'tj-chat__msg tj-chat__msg--' + who;
			var p = document.createElement('p');
			p.textContent = text;
			div.appendChild(p);
			body.appendChild(div);
			body.scrollTop = body.scrollHeight;
		}

		function showTyping() {
			var t = document.createElement('div');
			t.className = 'tj-chat__msg tj-chat__msg--bot tj-chat__typing';
			t.innerHTML = '<i></i><i></i><i></i>';
			t.id = 'tj-typing';
			body.appendChild(t);
			body.scrollTop = body.scrollHeight;
		}

		function hideTyping() {
			var t = document.getElementById('tj-typing');
			if (t) { t.remove(); }
		}

		function send(message) {
			if (busy || !message) { return; }
			busy = true;
			sendBtn.disabled = true;

			addMsg(message, 'user');
			history.push({ role: 'user', content: message });
			showTyping();

			fetch(cfg.endpoint, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': cfg.nonce
				},
				credentials: 'same-origin',
				body: JSON.stringify({ message: message, history: history.slice(0, -1) })
			})
				.then(function (res) { return res.json().then(function (data) { return { ok: res.ok, data: data }; }); })
				.then(function (res) {
					hideTyping();
					if (res.ok && res.data && res.data.reply) {
						addMsg(res.data.reply, 'bot');
						history.push({ role: 'assistant', content: res.data.reply });
					} else {
						var msg = (res.data && res.data.message) ? res.data.message : 'Maaf, terjadi kendala. Coba lagi ya.';
						addMsg(msg, 'bot');
					}
				})
				.catch(function () {
					hideTyping();
					addMsg('Koneksi bermasalah. Periksa internet Anda lalu coba lagi.', 'bot');
				})
				.finally(function () {
					busy = false;
					sendBtn.disabled = false;
					input.focus();
				});
		}

		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var message = input.value.trim();
			if (!message) { return; }
			input.value = '';
			send(message);
		});

		/* Quick replies */
		chat.querySelectorAll('.tj-chat__quick').forEach(function (btn) {
			btn.addEventListener('click', function () {
				send(btn.textContent.trim());
			});
		});
	}

	if ('loading' === document.readyState) {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
