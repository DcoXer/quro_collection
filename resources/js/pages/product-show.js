const csrf    = document.querySelector('meta[name="csrf-token"]').content;

document.addEventListener('DOMContentLoaded', function () {
    window.initCardTouch();
});
const cartUrl = document.querySelector('meta[name="cart-add-url"]').content;
let pageStock = 0;
let pageQty   = 1;

// ─── Gallery ──────────────────────────────────────────────────
window.switchGallery = function (index) {
    document.querySelectorAll('.product-gallery-slide').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.product-gallery-thumb').forEach(t => t.classList.remove('active'));

    const slide = document.querySelector(`.product-gallery-slide[data-index="${index}"]`);
    const thumb = document.querySelector(`.product-gallery-thumb[data-index="${index}"]`);

    if (slide) slide.classList.add('active');
    if (thumb) thumb.classList.add('active');
};

// ─── Page Size Selector ───────────────────────────────────────
window.selectSizePage = function (btn) {
    document.querySelectorAll('.page-size-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');

    const basePrice  = parseInt(btn.dataset.price);
    const flashPrice = btn.dataset.flashPrice ? parseInt(btn.dataset.flashPrice) : null;
    const stock      = parseInt(btn.dataset.stock);
    const displayEl  = document.getElementById('display-price');

    if (flashPrice !== null) {
        displayEl.textContent = 'Rp ' + flashPrice.toLocaleString('id-ID');
    } else {
        displayEl.textContent = 'Rp ' + basePrice.toLocaleString('id-ID');
    }

    const stockEl = document.getElementById('stock-info');
    if (stockEl) stockEl.textContent = 'Stok tersedia: ' + stock + ' pcs';

    // Set page form
    pageStock = stock;
    pageQty   = 1;
    document.getElementById('page-selected-size').value = btn.dataset.size;
    document.getElementById('page-qty-display').textContent = 1;
    document.getElementById('page-qty-input').value = 1;
};

// ─── Page Qty ─────────────────────────────────────────────────
window.changePageQty = function (delta) {
    const max = pageStock > 0 ? pageStock : 99;
    pageQty = Math.min(max, Math.max(1, pageQty + delta));
    document.getElementById('page-qty-display').textContent = pageQty;
    document.getElementById('page-qty-input').value         = pageQty;
};

// ─── Add to Cart (Page) ───────────────────────────────────────
function pageSetBtnLoading(loading) {
    const btn     = document.getElementById('page-add-btn');
    const spinner = document.getElementById('page-btn-spinner');
    const text    = document.getElementById('page-btn-text');
    if (!btn) return;
    btn.disabled = loading;
    spinner?.classList.toggle('hidden', !loading);
    if (text) text.textContent = loading ? 'Menambahkan...' : 'Tambah ke Keranjang';
}

window.addToCartPage = async function () {
    const size = document.getElementById('page-selected-size').value;
    if (!size) {
        showToast('Pilih size terlebih dahulu', 'error');
        const sizeWrap = document.querySelector('.page-size-btn')?.closest('.flex');
        sizeWrap?.classList.add('ring-2', 'ring-red-300', 'rounded-lg', 'p-1');
        setTimeout(() => sizeWrap?.classList.remove('ring-2', 'ring-red-300', 'rounded-lg', 'p-1'), 1500);
        return;
    }

    pageSetBtnLoading(true);

    // Tunggu 2 frame biar browser sempat render spinner sebelum fetch dimulai
    await new Promise(r => requestAnimationFrame(() => requestAnimationFrame(r)));

    const formData = new FormData(document.getElementById('page-cart-form'));
    const [res] = await Promise.all([
        guardedFetch(cartUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: formData,
        }),
        new Promise(r => setTimeout(r, 400)), // minimum 400ms loading biar keliatan
    ]);

    if (!res) { pageSetBtnLoading(false); return; }

    if (res.ok) {
        showToast('Produk ditambahkan ke keranjang', 'success');
        window.location.href = '/checkout';
    } else {
        pageSetBtnLoading(false);
        showToast('Gagal menambahkan ke keranjang', 'error');
    }
};