// Quick-view modal — shared between shop/index and shop/category
const csrf    = document.querySelector('meta[name="csrf-token"]').content;
const cartUrl = document.querySelector('meta[name="cart-add-url"]').content;

let qvProductId    = null;
let qvSelectedPrice = 0;
let qvSelectedStock = 0;
let qvQty          = 1;
let qvWishlistUrl  = null;
let qvInWishlist   = false;

window.openQuickView = async function (apiUrl, detailUrl) {
    document.getElementById('quick-view-modal').classList.remove('hidden');
    document.getElementById('qv-loading').classList.remove('hidden');
    document.getElementById('qv-content').classList.add('hidden');
    // qv-success removed — toast is used instead
    qvQty           = 1;
    qvSelectedPrice = 0;

    let p;
    try {
        const res = await fetch(apiUrl);
        p = await res.json();
    } catch (err) {
        console.error('Quick view fetch failed:', err);
        document.getElementById('qv-loading').classList.add('hidden');
        document.getElementById('quick-view-modal').classList.add('hidden');
        return;
    }

    qvProductId   = p.id;
    qvWishlistUrl = p.wishlist_url ?? null;
    qvInWishlist  = p.in_wishlist ?? false;
    qvSyncWishlistIcon();

    // Update recently viewed count di hero
    if (p.recentCount !== undefined) {
        document.querySelectorAll('.recent-viewed-count').forEach(el => {
            el.textContent = p.recentCount + ' produk';
        });
    }

    try {
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
        document.getElementById('qv-desc').textContent      = p.description ?? '';

        const priceEl     = document.getElementById('qv-price');
        const origPriceEl = document.getElementById('qv-price-original');
        if (p.flash_price_formatted) {
            priceEl.textContent = p.flash_price_formatted;
            priceEl.style.color = '#d97706'; // amber-600
            if (origPriceEl) {
                origPriceEl.textContent = p.price_formatted;
                origPriceEl.classList.remove('hidden');
            }
        } else {
            priceEl.textContent = p.price_formatted;
            priceEl.style.color = '';
            if (origPriceEl) origPriceEl.classList.add('hidden');
        }
        document.getElementById('qv-detail-link').href = detailUrl;

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
    } catch (domErr) {
        console.error('Quick view render error:', domErr);
    }

    document.getElementById('qv-loading').classList.add('hidden');
    document.getElementById('qv-content').classList.remove('hidden');
};

function qvSelectSize(btn) {
    document.querySelectorAll('.size-btn').forEach(b =>
        b.classList.remove('bg-gray-900', 'text-white', 'border-gray-900'));
    btn.classList.add('bg-gray-900', 'text-white', 'border-gray-900');
    qvSelectedPrice = parseInt(btn.dataset.price);
    qvSelectedStock = parseInt(btn.dataset.stock);
    document.getElementById('qv-selected-size').value = btn.dataset.size;

    // Reset qty ke 1 saat ganti size
    qvQty = 1;
    const qtyEl = document.getElementById('qv-qty-display');
    if (qtyEl) qtyEl.textContent = 1;

    // Tampilkan stok size yang dipilih
    const stockEl = document.getElementById('qv-stock-info');
    if (stockEl) {
        stockEl.textContent = 'Stok: ' + qvSelectedStock + ' pcs';
        stockEl.className = qvSelectedStock <= 5
            ? 'text-xs text-red-400 mt-2'
            : 'text-xs text-gray-400 mt-2';
        stockEl.classList.remove('hidden');
    }

    qvUpdateTotal();
}
window.qvSelectSize = qvSelectSize;

window.qvChangeQty = function (delta) {
    const max = qvSelectedStock > 0 ? qvSelectedStock : 99;
    qvQty = Math.min(max, Math.max(1, qvQty + delta));
    document.getElementById('qv-qty-display').textContent = qvQty;
    qvUpdateTotal();
};

function qvUpdateTotal() {
    if (qvSelectedPrice > 0) {
        document.getElementById('qv-total').textContent =
            'Rp ' + (qvSelectedPrice * qvQty).toLocaleString('id-ID');
    }
}

function qvSetBtnLoading(loading) {
    const btn     = document.getElementById('qv-add-btn');
    const spinner = document.getElementById('qv-btn-spinner');
    const text    = document.getElementById('qv-btn-text');
    if (!btn) return;
    btn.disabled = loading;
    spinner?.classList.toggle('hidden', !loading);
    if (text) text.textContent = loading ? 'Menambahkan...' : 'Tambah ke Keranjang';
}

function qvSetBtnSuccess() {
    const btn    = document.getElementById('qv-add-btn');
    const check  = document.getElementById('qv-btn-check');
    const text   = document.getElementById('qv-btn-text');
    if (!btn) return;
    btn.classList.remove('bg-gray-900', 'hover:bg-gray-700');
    btn.classList.add('bg-green-500');
    check?.classList.remove('hidden');
    if (text) text.textContent = 'Ditambahkan!';
}

window.qvSubmitCart = async function () {
    const size = document.getElementById('qv-selected-size').value;
    if (!size) {
        showToast('Pilih size terlebih dahulu', 'error');
        document.getElementById('qv-sizes')?.classList.add('ring-2', 'ring-red-300', 'rounded-lg', 'p-1');
        setTimeout(() => document.getElementById('qv-sizes')?.classList.remove('ring-2', 'ring-red-300', 'rounded-lg', 'p-1'), 1500);
        return;
    }

    qvSetBtnLoading(true);

    // Tunggu 2 frame biar browser sempat render spinner sebelum fetch dimulai
    await new Promise(r => requestAnimationFrame(() => requestAnimationFrame(r)));

    const formData = new FormData();
    formData.append('product_id', qvProductId);
    formData.append('size', size);
    formData.append('quantity', qvQty);

    const [res] = await Promise.all([
        guardedFetch(cartUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: formData,
        }),
        new Promise(r => setTimeout(r, 400)), // minimum 400ms loading biar keliatan
    ]);

    qvSetBtnLoading(false);

    if (!res) return;

    if (res.ok) {
        const data = await res.json();
        qvSetBtnSuccess();
        document.querySelectorAll('.cart-badge').forEach(badge => {
            badge.textContent = data.cartCount;
            badge.classList.remove('hidden');
            badge.classList.add('flex');
        });
        window.dispatchEvent(new CustomEvent('activity-updated'));
        setTimeout(() => {
            closeQuickView();
            showToast('Produk ditambahkan ke keranjang', 'success');
        }, 700);
    } else {
        showToast('Gagal menambahkan ke keranjang', 'error');
    }
};

function qvSyncWishlistIcon() {
    const icon = document.getElementById('qv-wishlist-icon');
    if (!icon) return;
    if (qvInWishlist) {
        icon.setAttribute('fill', 'currentColor');
        icon.classList.remove('text-gray-400');
        icon.classList.add('text-red-500');
    } else {
        icon.setAttribute('fill', 'none');
        icon.classList.remove('text-red-500');
        icon.classList.add('text-gray-400');
    }
}

window.qvToggleWishlist = async function () {
    if (!qvWishlistUrl) return;

    const prev = qvInWishlist;
    qvInWishlist = !prev;
    qvSyncWishlistIcon();

    try {
        const res  = await fetch(qvWishlistUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        });
        const data = await res.json();
        qvInWishlist = data.in_wishlist;
        qvSyncWishlistIcon();

        // Sync the grid card wishlist button for this product
        if (qvProductId) {
            const gridBtn = document.querySelector(`[data-product-id="${qvProductId}"]`);
            if (gridBtn && gridBtn.__x) {
                gridBtn.__x.$data.on = qvInWishlist;
            }
        }

        window.dispatchEvent(new CustomEvent('activity-updated'));
        showToast(qvInWishlist ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist', 'success');
    } catch {
        // revert on error
        qvInWishlist = prev;
        qvSyncWishlistIcon();
        showToast('Gagal memperbarui wishlist', 'error');
    }
};

window.closeQuickView = function () {
    document.getElementById('quick-view-modal').classList.add('hidden');
    const stockEl = document.getElementById('qv-stock-info');
    if (stockEl) stockEl.classList.add('hidden');
    qvSelectedStock = 0;
};
