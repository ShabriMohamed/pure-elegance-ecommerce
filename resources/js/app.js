import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

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
