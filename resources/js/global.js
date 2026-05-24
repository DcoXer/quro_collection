// ===== PRODUCT CARD SLIDER =====
const cardSliders = {};

window.slideCard = function (productId, direction) {
    const track = document.getElementById('track-' + productId);
    if (!track) return;

    const items = track.querySelectorAll('.slide-item');
    if (items.length <= 1) return;

    if (!cardSliders[productId]) cardSliders[productId] = 0;
    cardSliders[productId] = (cardSliders[productId] + direction + items.length) % items.length;
    track.style.transform = `translateX(-${cardSliders[productId] * 100}%)`;

    items.forEach((item, i) => {
        const video = item.querySelector('video');
        if (video) {
            if (i === cardSliders[productId]) video.play();
            else video.pause();
        }
    });
};

window.initCardTouch = function () {
    document.querySelectorAll('[data-slider][data-has-slides]').forEach(container => {
        if (container._touchBound) return;
        container._touchBound = true;

        const productId = container.dataset.slider;
        let startX = 0, startY = 0, dragX = 0, lockAxis = null;

        container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            dragX = 0;
            lockAxis = null;
        }, { passive: true });

        container.addEventListener('touchmove', (e) => {
            const dx = e.touches[0].clientX - startX;
            const dy = e.touches[0].clientY - startY;
            if (!lockAxis) lockAxis = Math.abs(dx) > Math.abs(dy) ? 'x' : 'y';
            if (lockAxis !== 'x') return;

            dragX = dx;
            const track = document.getElementById('track-' + productId);
            if (!track) return;
            const cur = cardSliders[productId] || 0;
            track.style.transition = 'none';
            track.style.transform = `translateX(calc(-${cur * 100}% + ${dragX}px))`;
        }, { passive: true });

        container.addEventListener('touchend', () => {
            if (lockAxis !== 'x') return;
            const track = document.getElementById('track-' + productId);
            if (!track) return;

            track.style.transition = 'transform 400ms cubic-bezier(0.25, 0.46, 0.45, 0.94)';

            if (Math.abs(dragX) > 50) {
                window.slideCard(productId, dragX < 0 ? 1 : -1);
                container.addEventListener('click', e => e.stopPropagation(), { once: true, capture: true });
            } else {
                const cur = cardSliders[productId] || 0;
                track.style.transform = `translateX(-${cur * 100}%)`;
            }
            dragX = 0;
            lockAxis = null;
        }, { passive: true });
    });
};

// ===== TOAST =====
let toastTimer;

window.showToast = function (message, type = 'success') {
    const toast = document.getElementById('toast');
    const icon  = document.getElementById('toast-icon');
    const msg   = document.getElementById('toast-message');

    const config = {
        success: { icon: '✓', border: 'border-green-100', text: 'text-green-700' },
        error:   { icon: '✕', border: 'border-red-100',   text: 'text-red-600'   },
        info:    { icon: 'ℹ', border: 'border-blue-100',  text: 'text-blue-600'  },
    };

    const c = config[type] || config.success;

    toast.className = toast.className
        .replace(/border-\w+-100/g, '')
        .replace(/text-\w+-\d+/g, '');

    toast.classList.add(c.border, c.text);
    icon.textContent = c.icon;
    msg.textContent  = message;

    toast.classList.remove('translate-x-[200%]');
    toast.classList.add('translate-x-0');

    clearTimeout(toastTimer);
    toastTimer = setTimeout(window.hideToast, 3000);
};

window.hideToast = function () {
    const toast = document.getElementById('toast');
    toast.classList.remove('translate-x-0');
    toast.classList.add('translate-x-[200%]');
};

// ===== CONFIRM =====
let confirmCallback = null;

window.showConfirm = function (title, message, callback) {
    document.getElementById('confirm-title').textContent   = title;
    document.getElementById('confirm-message').textContent = message;
    confirmCallback = callback;
    document.getElementById('confirm-modal').classList.remove('hidden');
};

window.closeConfirm = function () {
    document.getElementById('confirm-modal').classList.add('hidden');
    confirmCallback = null;
};

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('confirm-ok')?.addEventListener('click', function () {
        if (confirmCallback) confirmCallback();
        window.closeConfirm();
    });
});

// ===== LOADING GUARD =====
const _pendingRequests = new Set();

window.guardedFetch = async function(url, options = {}) {
    const key = (options.method || 'GET') + ':' + url;
    if (_pendingRequests.has(key)) return null; // block duplicate
    _pendingRequests.add(key);
    try {
        return await fetch(url, options);
    } finally {
        _pendingRequests.delete(key);
    }
};