import './bootstrap';

// ========================================
// Shared search helpers
// The command-palette search markup lives in each layout, but the XSS-escaping
// and highlight logic is defined ONCE here (was duplicated in both layouts).
// ========================================
window.peEscHtml = function (s) {
    return String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
};

window.peHighlight = function (text, query) {
    const safe = window.peEscHtml(text);
    if (!query || !text) return safe;
    const esc = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    return safe.replace(new RegExp(`(${esc})`, 'gi'), '<mark>$1</mark>');
};

// ========================================
// Mobile Drawer Menu Toggle
// ========================================
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('menu-toggle');
    const drawer = document.getElementById('mobile-drawer');
    const overlay = document.getElementById('drawer-overlay');
    const closeBtn = document.getElementById('drawer-close');

    function openDrawer() {
        if (drawer) drawer.classList.add('open');
        if (overlay) overlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        if (drawer) drawer.classList.remove('open');
        if (overlay) overlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    if (toggle) toggle.addEventListener('click', openDrawer);
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
    if (overlay) overlay.addEventListener('click', closeDrawer);

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDrawer();
    });
});

// ========================================
// Fluid Motion Layer
// - header elevation on scroll
// - back-to-top control
// - image fade-in on load
// ========================================
document.addEventListener('DOMContentLoaded', () => {
    // Header elevation
    const header = document.querySelector('.store-header');
    const backToTop = document.getElementById('back-to-top');

    const onScroll = () => {
        if (header) header.classList.toggle('is-scrolled', window.scrollY > 8);
        if (backToTop) backToTop.classList.toggle('visible', window.scrollY > 600);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    backToTop?.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Image fade-in: only for images still loading — cached images stay visible,
    // so there is never a blank flash.
    document.querySelectorAll('main img').forEach((img) => {
        if (img.complete) return;
        img.classList.add('img-loading');
        const reveal = () => img.classList.replace('img-loading', 'img-loaded');
        img.addEventListener('load', reveal, { once: true });
        img.addEventListener('error', reveal, { once: true }); // never leave a hidden broken image
    });

    // Broken-image fallback: swap any image that fails to load to the placeholder.
    // Replaces the per-render file_exists() disk stat that used to run server-side.
    const PLACEHOLDER = '/images/placeholder.svg';
    document.querySelectorAll('img').forEach((img) => {
        if (img.complete && img.naturalWidth === 0 && !img.src.endsWith(PLACEHOLDER)) {
            img.src = PLACEHOLDER;
        }
    });
});

// 'error' does not bubble, so listen in the capture phase for any image that 404s.
document.addEventListener('error', (e) => {
    const el = e.target;
    if (el && el.tagName === 'IMG' && el.dataset.fallback !== '1' && !el.src.endsWith('/images/placeholder.svg')) {
        el.dataset.fallback = '1';
        el.src = '/images/placeholder.svg';
    }
}, true);

// ========================================
// Toast Notification System
// ========================================
window.showToast = function(message, type = 'success') {
    // Create toast container if it doesn't exist
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.position = 'fixed';
        container.style.bottom = '20px';
        container.style.right = '20px';
        container.style.zIndex = '9999';
        container.style.display = 'flex';
        container.style.flexDirection = 'column';
        container.style.gap = '10px';
        document.body.appendChild(container);
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast-message ${type}`;
    
    // Icon based on type
    const iconName = type === 'success' ? 'check_circle' : 'info';
    
    toast.innerHTML = `
        <span class="material-symbols-outlined">${iconName}</span>
        <span>${message}</span>
    `;

    // Styling
    toast.style.display = 'flex';
    toast.style.alignItems = 'center';
    toast.style.gap = '8px';
    toast.style.background = type === 'success' ? 'var(--color-rich-black)' : 'var(--color-error)';
    toast.style.color = 'var(--color-pure-white)';
    toast.style.padding = '12px 20px';
    toast.style.borderRadius = '4px';
    toast.style.fontFamily = 'var(--font-sans)';
    toast.style.fontSize = '0.9rem';
    toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    toast.style.transform = 'translateY(100px)';
    toast.style.opacity = '0';
    toast.style.transition = 'all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55)';

    container.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateY(0)';
        toast.style.opacity = '1';
    }, 10);

    // Animate out and remove
    setTimeout(() => {
        toast.style.transform = 'translateY(20px)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
};

// ========================================
// Dynamic Real-Time Wishlist Toggle
// ========================================
window.toggleWishlist = async function(productId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    try {
        const response = await fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json' // Force JSON response
            },
            body: JSON.stringify({ product_id: productId })
        });

        const data = await response.json();

        if (response.ok) {
            // Find all instances of this product's heart icon on the page
            // (could be in a grid, or on the product page itself)
            const btnIcons = document.querySelectorAll(`button[onclick*="toggleWishlist(${productId})"] .material-symbols-outlined`);
            
            btnIcons.forEach(icon => {
                if (data.status === 'added') {
                    icon.textContent = 'favorite';
                    icon.style.color = 'var(--color-error)'; // Fill color
                } else {
                    icon.textContent = 'favorite_border';
                    icon.style.color = ''; // Reset to default
                }
            });

            showToast(data.message, 'success');
        } else {
            showToast('Error updating wishlist', 'error');
        }
    } catch (error) {
        console.error('Wishlist error:', error);
        showToast('A network error occurred.', 'error');
    }
};

// ========================================
// WhatsApp order handoff
// Navigation to wa.me goes through the anchor's own href (a top-level navigation,
// which CSP form-action does NOT govern). The "handoff opened" state change is a
// separate same-origin fetch (governed by connect-src 'self'). This intentionally
// replaces the old <form> that redirected off-site — a pattern that CSP
// `form-action 'self'` blocks because the directive also applies to redirect targets.
// ========================================
(function () {
    function markHandoff(markUrl) {
        if (!markUrl) return;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        try {
            // keepalive lets the POST survive the imminent navigation to wa.me.
            fetch(markUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                keepalive: true,
            }).catch(() => {});
        } catch (e) { /* best-effort: the anchor navigation still proceeds */ }
    }

    // Manual button/link: record the handoff, then let the anchor navigate normally.
    document.addEventListener('click', function (e) {
        const link = e.target.closest('[data-wa-open]');
        if (!link) return;
        markHandoff(link.getAttribute('data-wa-mark'));
    }, true);

    // Auto-open (confirmation page): show the order number for a beat, then open
    // WhatsApp in the same tab — a timed, gesture-less window.open would be blocked.
    document.addEventListener('DOMContentLoaded', function () {
        const auto = document.querySelector('[data-wa-auto]');
        if (!auto) return;
        const url = auto.getAttribute('href');
        const markUrl = auto.getAttribute('data-wa-mark');
        setTimeout(function () {
            markHandoff(markUrl);
            if (url) window.location.href = url;
        }, 1500);
    });

    // Returning via Back (bfcache) should reflect the true post-handoff state.
    window.addEventListener('pageshow', function (e) {
        if (e.persisted && document.querySelector('[data-wa-auto]')) window.location.reload();
    });
})();

// ========================================
// Shop-by-Brand logo strip
// Arrow buttons page the rail horizontally; they hide at each end so users aren't
// offered a control that does nothing. Touch users just swipe (arrows are hidden
// via CSS on coarse pointers).
// ========================================
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('brand-strip-track');
    const left = document.getElementById('brand-strip-left');
    const right = document.getElementById('brand-strip-right');
    if (!track || !left || !right) return;

    const page = () => Math.max(track.clientWidth * 0.8, 200);

    const syncArrows = () => {
        const maxScroll = track.scrollWidth - track.clientWidth;
        // Nothing to scroll: drop both controls entirely.
        const scrollable = maxScroll > 4;
        left.hidden = !scrollable || track.scrollLeft <= 2;
        right.hidden = !scrollable || track.scrollLeft >= maxScroll - 2;
    };

    left.addEventListener('click', () => track.scrollBy({ left: -page(), behavior: 'smooth' }));
    right.addEventListener('click', () => track.scrollBy({ left: page(), behavior: 'smooth' }));

    track.addEventListener('scroll', syncArrows, { passive: true });
    window.addEventListener('resize', syncArrows);
    syncArrows();
});
