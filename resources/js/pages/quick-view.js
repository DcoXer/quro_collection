// Quick-view modal — shared between shop/index and shop/category
const csrf    = document.querySelector('meta[name="csrf-token"]').content;
const cartUrl = document.querySelector('meta[name="cart-add-url"]').content;

let qvProductId    = null;
let qvSelectedPrice = 0;
let qvQty          = 1;

window.openQuickView = async function (apiUrl, detailUrl) {
    document.getElementById('quick-view-modal').classList.remove('hidden');
    document.getElementById('qv-loading').classList.remove('hidden');
    document.getElementById('qv-content').classList.add('hidden');
    document.getElementById('qv-success').classList.add('hidden');
    qvQty           = 1;
    qvSelectedPrice = 0;

    const res = await fetch(apiUrl);
    const p   = await res.json();

    qvProductId = p.id;

    if (p.image) {
        document.getElementById('qv-image').src = p.image;
        document.getElementById('qv-image').classList.remove('hidden');
        document.getElementById('qv-no-image').classList.add('hidden');
    } else {
        document.getElementById('qv-image').classList.add('hidden');
        document.getElementById('qv-no-image').classList.remove('hidden');
    }

    document.getElementById('qv-category').textContent  = p.category ?? '';
    document.getElementById('qv-name').textContent      = p.name;
    document.getElementById('qv-price').textContent     = p.price_formatted;
    document.getElementById('qv-desc').textContent      = p.description ?? '';
    document.getElementById('qv-detail-link').href      = detailUrl;

    const sizeEl = document.getElementById('qv-selected-size');
    if (sizeEl) sizeEl.value = '';
    const qtyEl = document.getElementById('qv-qty-display');
    if (qtyEl) qtyEl.textContent = 1;
    const totalEl = document.getElementById('qv-total');
    if (totalEl) totalEl.textContent = 'Pilih size';

    const sizesEl = document.getElementById('qv-sizes');
    if (sizesEl) {
        sizesEl.innerHTML = '';

        p.variants.forEach(v => {
            const btn          = document.createElement('button');
            btn.type           = 'button';
            btn.textContent    = v.size;
            btn.dataset.price  = v.effective_price;
            btn.dataset.size   = v.size;
            btn.dataset.stock  = v.stock;

            if (v.stock > 0) {
                btn.className = 'size-btn px-4 py-2 border border-gray-200 rounded-lg text-sm hover:border-gray-900 transition';
                btn.onclick   = () => qvSelectSize(btn);
            } else {
                btn.className = 'px-4 py-2 border border-gray-100 rounded-lg text-sm text-gray-300 cursor-not-allowed';
                btn.disabled  = true;
            }
            sizesEl.appendChild(btn);
        });
    }

    document.getElementById('qv-loading').classList.add('hidden');
    document.getElementById('qv-content').classList.remove('hidden');
};

function qvSelectSize(btn) {
    document.querySelectorAll('.size-btn').forEach(b =>
        b.classList.remove('bg-gray-900', 'text-white', 'border-gray-900'));
    btn.classList.add('bg-gray-900', 'text-white', 'border-gray-900');
    qvSelectedPrice = parseInt(btn.dataset.price);
    document.getElementById('qv-selected-size').value = btn.dataset.size;
    qvUpdateTotal();
}
window.qvSelectSize = qvSelectSize;

window.qvChangeQty = function (delta) {
    qvQty = Math.max(1, qvQty + delta);
    document.getElementById('qv-qty-display').textContent = qvQty;
    qvUpdateTotal();
};

function qvUpdateTotal() {
    if (qvSelectedPrice > 0) {
        document.getElementById('qv-total').textContent =
            'Rp ' + (qvSelectedPrice * qvQty).toLocaleString('id-ID');
    }
}

window.qvSubmitCart = async function () {
    const size = document.getElementById('qv-selected-size').value;
    if (!size) {
        showToast('Pilih size terlebih dahulu', 'error');
        document.getElementById('qv-sizes')?.classList.add('ring-2', 'ring-red-300', 'rounded-lg', 'p-1');
        setTimeout(() => document.getElementById('qv-sizes')?.classList.remove('ring-2', 'ring-red-300', 'rounded-lg', 'p-1'), 1500);
        return;
    }

    const formData = new FormData();
    formData.append('product_id', qvProductId);
    formData.append('size', size);
    formData.append('quantity', qvQty);

    const res = await fetch(cartUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: formData,
    });

    if (res.ok) {
        const data  = await res.json();
        document.getElementById('qv-success').classList.remove('hidden');
        document.getElementById('qv-content')
            .querySelector('button[onclick="qvSubmitCart()"]')
            ?.closest('.flex-col')?.querySelector('.mb-3')?.classList.add('hidden');

        const badge = document.querySelector('.cart-badge');
        if (badge) badge.textContent = data.cartCount;
    } else {
        showToast('Gagal menambahkan ke keranjang', 'error');
    }
};

window.closeQuickView = function () {
    document.getElementById('quick-view-modal').classList.add('hidden');
};
