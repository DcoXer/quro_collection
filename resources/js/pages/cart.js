const qtyTimers = {};
const csrf      = document.querySelector('meta[name="csrf-token"]').content;

window.changeQty = async function (btn, delta) {
    const form    = btn.closest('form');
    const display = form.querySelector('.qty-display');
    const input   = form.querySelector('input[name="quantity"]');
    const key     = form.action;
    const newVal  = Math.max(1, parseInt(display.textContent) + delta);

    display.textContent = newVal;
    input.value         = newVal;

    clearTimeout(qtyTimers[key]);
    qtyTimers[key] = setTimeout(async () => {
        const res = await fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf },
            body: new FormData(form),
        });

        if (res.ok) {
            updateCartTotal();
        } else {
            display.textContent = parseInt(input.value) - delta;
            input.value         = parseInt(input.value) - delta;
            window.showToast?.('Gagal update quantity', 'error');
        }
    }, 300);

    updateCartTotal();
};

function updateCartTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const priceEl = row.querySelector('[data-price]');
        const qtyEl   = row.querySelector('.qty-display');
        if (priceEl && qtyEl) {
            total += parseInt(priceEl.dataset.price) * parseInt(qtyEl.textContent);
        }
    });

    const totalEl      = document.getElementById('cart-total');
    const finalTotalEl = document.getElementById('final-total');

    if (totalEl) totalEl.textContent      = 'Rp ' + total.toLocaleString('id-ID');
    if (finalTotalEl) finalTotalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
}
