/**
 * Pure Elegance — landing page behaviour.
 *
 * Three independent controllers, each a no-op when its markup is absent:
 *   1. Hero carousel  — autoplay, arrows/dots/keyboard/swipe, honest progress bar
 *   2. Scroll reveal  — IntersectionObserver, one-shot
 *   3. Newsletter     — double-submit guard
 *
 * Every animation here runs on transform/opacity only, and everything either
 * stops or is skipped outright under prefers-reduced-motion.
 */

const prefersReducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ------------------------------------------------------------------------
   1. Hero carousel
------------------------------------------------------------------------ */
function initHeroCarousel() {
    const hero = document.querySelector('[data-hero]');
    if (!hero) return;

    const slides = Array.from(hero.querySelectorAll('[data-hero-slide]'));
    if (slides.length < 2) return;

    const dots = Array.from(hero.querySelectorAll('[data-hero-dot]'));
    const prevBtn = hero.querySelector('[data-hero-prev]');
    const nextBtn = hero.querySelector('[data-hero-next]');
    const progress = hero.querySelector('[data-hero-progress]');
    const live = hero.querySelector('[data-hero-live]');

    const INTERVAL = 6800;
    const SWIPE_THRESHOLD = 45;

    let current = 0;
    let timer = null;
    let touchStartX = null;
    let touchStartY = null;

    /* The carousel is held by several independent things at once — a hovering
       pointer, keyboard focus, a hidden tab, being scrolled off screen. Tracking
       them in a set means the last one to let go is the one that resumes it,
       instead of a stray mouseleave restarting a carousel the tab still has
       hidden. */
    const holds = new Set();

    const isPaused = () => holds.size > 0;

    function restartProgress() {
        if (!progress) return;
        progress.style.animation = 'none';
        // Force a reflow so the bar restarts rather than continuing its old run.
        void progress.offsetWidth;
        progress.style.animation = `peHeroProgress ${INTERVAL}ms linear forwards`;
    }

    function render(index) {
        current = (index + slides.length) % slides.length;

        slides.forEach((slide, i) => {
            const active = i === current;
            slide.classList.toggle('is-active', active);
            slide.setAttribute('aria-hidden', active ? 'false' : 'true');
            // CSS visibility already removes inactive slides from the tab order;
            // inert makes that explicit where it is supported.
            if ('inert' in slide) slide.inert = !active;
        });

        dots.forEach((dot, i) => {
            const active = i === current;
            dot.classList.toggle('is-active', active);
            dot.setAttribute('aria-selected', active ? 'true' : 'false');
        });

        if (live) live.textContent = `Highlight ${current + 1} of ${slides.length}`;
        restartProgress();
    }

    function clearTimer() {
        window.clearInterval(timer);
        timer = null;
    }

    function syncPlayState() {
        const paused = isPaused();
        hero.dataset.paused = paused ? 'true' : 'false';

        /* Freeze the bar with the timer. Left running it kept filling, promising
           an advance that was never coming. This is set inline rather than from
           a [data-paused] stylesheet rule because restartProgress() writes the
           `animation` shorthand inline, and that shorthand resets
           animation-play-state to running — inline always winning over the
           stylesheet. One owner for the property avoids the fight. */
        if (progress) {
            progress.style.animationPlayState = paused ? 'paused' : 'running';
        }

        if (paused || prefersReducedMotion()) {
            clearTimer();
            return;
        }
        if (!timer) {
            timer = window.setInterval(() => render(current + 1), INTERVAL);
        }
    }

    const hold = (reason) => { holds.add(reason); syncPlayState(); };
    const release = (reason) => { holds.delete(reason); syncPlayState(); };

    /** Move, then restart the clock so the new slide gets its full dwell. */
    function goTo(index) {
        render(index);
        clearTimer();
        syncPlayState();
    }

    prevBtn?.addEventListener('click', () => goTo(current - 1));
    nextBtn?.addEventListener('click', () => goTo(current + 1));
    dots.forEach((dot) => {
        dot.addEventListener('click', () => goTo(Number(dot.dataset.heroDot) || 0));
    });

    hero.addEventListener('mouseenter', () => hold('hover'));
    hero.addEventListener('mouseleave', () => release('hover'));
    hero.addEventListener('focusin', () => hold('focus'));
    hero.addEventListener('focusout', (event) => {
        if (!hero.contains(event.relatedTarget)) release('focus');
    });

    hero.addEventListener('keydown', (event) => {
        const moves = {
            ArrowLeft: () => current - 1,
            ArrowRight: () => current + 1,
            Home: () => 0,
            End: () => slides.length - 1,
        };
        const move = moves[event.key];
        if (!move) return;
        event.preventDefault();
        goTo(move());
    });

    hero.addEventListener('touchstart', (event) => {
        const touch = event.changedTouches[0];
        touchStartX = touch?.clientX ?? null;
        touchStartY = touch?.clientY ?? null;
        hold('touch');
    }, { passive: true });

    hero.addEventListener('touchend', (event) => {
        if (touchStartX !== null) {
            const touch = event.changedTouches[0];
            const dx = (touch?.clientX ?? touchStartX) - touchStartX;
            const dy = (touch?.clientY ?? touchStartY) - touchStartY;
            // Only treat it as a swipe when the gesture is mostly horizontal,
            // so a vertical scroll down the page never flips the slide.
            if (Math.abs(dx) > SWIPE_THRESHOLD && Math.abs(dx) > Math.abs(dy)) {
                render(current + (dx < 0 ? 1 : -1));
            }
        }
        touchStartX = null;
        touchStartY = null;
        release('touch');
    }, { passive: true });

    document.addEventListener('visibilitychange', () => {
        document.hidden ? hold('tab') : release('tab');
    });

    // Stop animating once the hero has scrolled away — there is no reason to
    // run a timer and a paint loop for something nobody can see.
    if ('IntersectionObserver' in window) {
        new IntersectionObserver(
            ([entry]) => (entry.isIntersecting ? release('offscreen') : hold('offscreen')),
            { threshold: 0.15 }
        ).observe(hero);
    }

    // Honour a reduced-motion preference toggled mid-session.
    const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
    motionQuery.addEventListener?.('change', syncPlayState);

    render(0);
    syncPlayState();
}

/* ------------------------------------------------------------------------
   2. Scroll reveal
------------------------------------------------------------------------ */
function initScrollReveal() {
    const root = document.documentElement;
    const targets = document.querySelectorAll('.pe-reveal');
    if (!targets.length) return;

    // No IntersectionObserver (or reduced motion): drop the opt-in attribute so
    // the CSS never hides anything, and leave the page fully visible.
    if (!('IntersectionObserver' in window) || prefersReducedMotion()) {
        root.removeAttribute('data-reveal');
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        /* Liveness is claimed on the first callback, never at construction.
           An observer that exists but never fires (some embedded and headless
           renderers never compute intersections) would otherwise leave the whole
           page sitting at opacity 0 with the template's failsafe disarmed.
           observe() reports every target's initial state immediately, so one
           callback is proof the API really works here. */
        root.setAttribute('data-reveal-active', '');

        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-in');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -60px 0px' });

    targets.forEach((el) => observer.observe(el));
}

/* ------------------------------------------------------------------------
   3. Newsletter
------------------------------------------------------------------------ */
function initNewsletter() {
    const form = document.querySelector('[data-newsletter]');
    if (!form) return;

    form.addEventListener('submit', () => {
        const button = form.querySelector('button[type="submit"]');
        if (!button || button.disabled) return;
        // Guards the double-tap that would otherwise burn a second request
        // against the 5/minute throttle on this route.
        button.disabled = true;
        button.textContent = 'Subscribing…';
    });
}

function boot() {
    initHeroCarousel();
    initScrollReveal();
    initNewsletter();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
