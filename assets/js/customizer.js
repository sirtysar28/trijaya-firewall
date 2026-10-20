/**
 * Trijaya Firewall v1.1.0 — Customizer live preview (opsional).
 */
(function ($, wp) {
	'use strict';

	if (!wp || !wp.customize) { return; }

	wp.customize('blogname', function (value) {
		value.bind(function (to) {
			$('.tj-site-name').text(to);
		});
	});
})(jQuery, window.wp);
