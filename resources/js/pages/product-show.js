const csrf    = document.querySelector('meta[name="csrf-token"]').content;
const cartUrl = document.querySelector('meta[name="cart-add-url"]').content;
let selectedPrice = 0;
let qty           = 1;

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

    const price = parseInt(btn.dataset.price);
    document.getElementById('display-price').textContent =
        'Rp ' + price.toLocaleString('id-ID');
};

// ─── Modal Size Selector ──────────────────────────────────────
window.selectSize = function (btn) {
    document.querySelectorAll('.size-btn').forEach(b =>
        b.classList.remove('bg-gray-900', 'text-white', 'border-gray-900'));
    btn.classList.add('bg-gray-900', 'text-white', 'border-gray-900');
    selectedPrice = parseInt(btn.dataset.price);
    document.getElementById('selected-size').value = btn.dataset.size;
    updateTotal();
};

// ─── Qty ──────────────────────────────────────────────────────
window.changeQty = function (delta) {
    qty = Math.max(1, qty + delta);
    document.getElementById('qty-display').textContent = qty;
    document.getElementById('qty-input').value         = qty;
    updateTotal();
};

function updateTotal() {
    if (selectedPrice > 0) {
        document.getElementById('modal-total').textContent =
            'Rp ' + (selectedPrice * qty).toLocaleString('id-ID');
    }
}

// ─── Modal ────────────────────────────────────────────────────
window.closeModal = function () {
    document.getElementById('modal-add-cart').classList.add('hidden');
    document.getElementById('cart-success').classList.add('hidden');
    document.getElementById('form-add-cart').classList.remove('hidden');
    qty           = 1;
    selectedPrice = 0;
    document.getElementById('qty-display').textContent = 1;
    document.getElementById('qty-input').value         = 1;
    document.getElementById('modal-total').textContent = 'Pilih size dulu';
    document.querySelectorAll('.size-btn').forEach(b =>
        b.classList.remove('bg-gray-900', 'text-white', 'border-gray-900'));
};

// ─── Submit Cart ──────────────────────────────────────────────
window.submitCart = async function () {
    const size = document.getElementById('selected-size').value;
    if (!size) {
        showToast('Pilih size terlebih dahulu', 'error');
        const sizeWrap = document.querySelector('#form-add-cart .flex.flex-wrap');
        sizeWrap?.classList.add('ring-2', 'ring-red-300', 'rounded-lg', 'p-1');
        setTimeout(() => sizeWrap?.classList.remove('ring-2', 'ring-red-300', 'rounded-lg', 'p-1'), 1500);
        return;
    }
    const formData = new FormData(document.getElementById('form-add-cart'));
    const res = await fetch(cartUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: formData,
    });
    if (res.ok) {
        document.getElementById('form-add-cart').classList.add('hidden');
        document.getElementById('cart-success').classList.remove('hidden');
    } else {
        showToast('Gagal menambahkan ke keranjang', 'error');
    }
};