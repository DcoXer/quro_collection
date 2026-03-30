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
