/**
 * Trijaya Firewall v1.4.2 — Main JS
 * Nav mobile, sticky header, reveal on scroll, counter, particles.
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {

		/* ---------- Sticky header ---------- */
		var header = document.querySelector('.tj-header');
		if (header) {
			var onScroll = function () {
				header.classList.toggle('is-stuck', window.scrollY > 10);
			};
			window.addEventListener('scroll', onScroll, { passive: true });
			onScroll();
		}

		/* ---------- Mobile nav ---------- */
		var burger = document.querySelector('.tj-burger');
		if (burger) {
			burger.addEventListener('click', function () {
				var open = document.body.classList.toggle('tj-nav-open');
				burger.setAttribute('aria-expanded', open ? 'true' : 'false');
			});

			// Tutup saat klik overlay (klik di area gelap).
			document.addEventListener('click', function (e) {
				if (document.body.classList.contains('tj-nav-open') && e.target === document.body) {
					document.body.classList.remove('tj-nav-open');
					burger.setAttribute('aria-expanded', 'false');
				}
			});

			// Sub-menu accordion di mobile (harus sama dengan breakpoint CSS drawer: 1024px).
			document.querySelectorAll('.tj-nav__menu .menu-item-has-children > a').forEach(function (link) {
				link.addEventListener('click', function (e) {
					if (window.innerWidth <= 1024) {
						e.preventDefault();
						var li = link.parentElement;
						li.classList.toggle('is-open');
						link.setAttribute('aria-expanded', li.classList.contains('is-open') ? 'true' : 'false');
					}
				});
			});

			// Tutup menu saat resize ke desktop.
			window.addEventListener('resize', function () {
				if (window.innerWidth > 1024) {
					document.body.classList.remove('tj-nav-open');
					burger.setAttribute('aria-expanded', 'false');
				}
			});
		}

		/* ---------- Reveal on scroll ---------- */
		var revealEls = document.querySelectorAll('.tj-reveal, .tj-service');
		if ('IntersectionObserver' in window && revealEls.length) {
			var io = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-in');
						io.unobserve(entry.target);
					}
				});
			}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
			revealEls.forEach(function (el) { io.observe(el); });
		} else {
			revealEls.forEach(function (el) { el.classList.add('is-in'); });
		}

		/* ---------- Animated counters ---------- */
		var counters = document.querySelectorAll('.tj-counter');
		var animateCounter = function (el) {
			var target = parseInt(el.getAttribute('data-target'), 10) || 0;
			var duration = 1800;
			var start = null;
			var step = function (ts) {
				if (!start) { start = ts; }
				var progress = Math.min((ts - start) / duration, 1);
				var eased = 1 - Math.pow(1 - progress, 3);
				el.textContent = Math.round(eased * target).toLocaleString('id-ID');
				if (progress < 1) { requestAnimationFrame(step); }
			};
			requestAnimationFrame(step);
		};
		if ('IntersectionObserver' in window && counters.length) {
			var cio = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						animateCounter(entry.target);
						cio.unobserve(entry.target);
					}
				});
			}, { threshold: 0.6 });
			counters.forEach(function (el) { cio.observe(el); });
		}

		/* ---------- Hero particles ---------- */
		var particleBox = document.getElementById('tj-particles');
		if (particleBox && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
			for (var i = 0; i < 18; i++) {
				var p = document.createElement('span');
				p.className = 'tj-particle' + (i % 3 === 0 ? ' tj-particle--cyan' : '');
				p.style.left = (Math.random() * 100).toFixed(2) + '%';
				p.style.bottom = '-10px';
				p.style.animationDelay = (Math.random() * 9).toFixed(2) + 's';
				p.style.animationDuration = (7 + Math.random() * 7).toFixed(2) + 's';
				var scale = 0.6 + Math.random() * 1.2;
				p.style.transform = 'scale(' + scale.toFixed(2) + ')';
				particleBox.appendChild(p);
			}
		}

		/* ---------- Smooth scroll untuk anchor (#layanan dst.) ---------- */
		document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(function (anchor) {
			anchor.addEventListener('click', function (e) {
				var target = document.querySelector(anchor.getAttribute('href'));
				if (target) {
					e.preventDefault();
					var top = target.getBoundingClientRect().top + window.scrollY - 90;
					window.scrollTo({ top: top, behavior: 'smooth' });
					document.body.classList.remove('tj-nav-open');
				}
			});
		});
	});
})();
