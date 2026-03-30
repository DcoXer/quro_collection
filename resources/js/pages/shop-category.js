// Category page product search
// Search URL is read from data-search-url on #product-grid
let searchTimer;

window.fetchProducts = function () {
    const grid    = document.getElementById('product-grid');
    const search  = document.getElementById('search-input').value;
    const url     = grid.dataset.searchUrl;

    document.getElementById('reset-filter').classList.toggle('hidden', !search);

    clearTimeout(searchTimer);
    searchTimer = setTimeout(async () => {
        const params = new URLSearchParams();
        if (search) params.set('search', search);

        const res  = await fetch(`${url}?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        const html = await res.text();
        grid.innerHTML = html;
    }, 200);
};

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('search-input')?.addEventListener('input', window.fetchProducts);
    document.getElementById('reset-filter')?.addEventListener('click', function () {
        document.getElementById('search-input').value = '';
        window.fetchProducts();
    });
});
