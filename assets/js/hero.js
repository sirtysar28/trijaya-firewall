/**
 * Trijaya Firewall v1.1.0 — Hero Slider
 * Aktif otomatis bila hero berisi lebih dari 1 slide.
 * Fitur: autoplay, panah, dots, progress bar, swipe, pause on hover,
 *        dukungan keyboard & reduced-motion.
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var hero = document.querySelector('.tj-hero[data-slider="on"]');
		if (!hero) { return; }

		var slides = Array.prototype.slice.call(hero.querySelectorAll('.tj-hero__slide'));
		if (slides.length < 2) { return; }

		var dotsWrap = hero.querySelector('.tj-hero__dots');
		var prevBtn = hero.querySelector('.tj-hero__prev');
		var nextBtn = hero.querySelector('.tj-hero__next');
		var progress = hero.querySelector('.tj-hero__progress');
		var current = 0;
		var timer = null;
		var DELAY = 6500;
		var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		/* Dots */
		slides.forEach(function (slide, index) {
			var dot = document.createElement('button');
			dot.type = 'button';
			dot.className = 'tj-hero__dot' + (0 === index ? ' is-active' : '');
			dot.setAttribute('role', 'tab');
			dot.setAttribute('aria-label', 'Slide ' + (index + 1));
			dot.addEventListener('click', function () { go(index, true); });
			dotsWrap.appendChild(dot);
		});
		var dots = Array.prototype.slice.call(dotsWrap.children);

		function render() {
			slides.forEach(function (slide, index) {
				slide.classList.toggle('is-active', index === current);
				slide.setAttribute('aria-hidden', index === current ? 'false' : 'true');
			});
			dots.forEach(function (dot, index) {
				dot.classList.toggle('is-active', index === current);
			});
			restartProgress();
		}

		function go(index, manual) {
			current = (index + slides.length) % slides.length;
			render();
			if (manual) { restart(); }
		}

		function next() { go(current + 1, false); }
		function prev() { go(current - 1, false); }

		function restartProgress() {
			if (!progress) { return; }
			progress.style.animation = 'none';
			void progress.offsetWidth; /* reflow */
			if (!reduced) { progress.style.animation = null; }
		}

		function restart() {
			clearTimeout(timer);
			if (!reduced) {
				timer = setTimeout(next, DELAY);
			}
		}

		/* Controls */
		if (nextBtn) { nextBtn.addEventListener('click', function () { next(); restart(); }); }
		if (prevBtn) { prevBtn.addEventListener('click', function () { prev(); restart(); }); }

		hero.addEventListener('mouseenter', function () { clearTimeout(timer); });
		hero.addEventListener('mouseleave', restart);

		document.addEventListener('keydown', function (e) {
			if (!hero.offsetParent) { return; }
			if ('ArrowRight' === e.key) { next(); restart(); }
			if ('ArrowLeft' === e.key) { prev(); restart(); }
		});

		/* Swipe */
		var startX = null;
		hero.addEventListener('touchstart', function (e) {
			startX = e.touches[0].clientX;
		}, { passive: true });
		hero.addEventListener('touchend', function (e) {
			if (null === startX) { return; }
			var dx = e.changedTouches[0].clientX - startX;
			if (Math.abs(dx) > 48) {
				if (dx < 0) { next(); } else { prev(); }
				restart();
			}
			startX = null;
		}, { passive: true });

		render();
		restart();
	});
})();
