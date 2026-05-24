// ─── Hero Carousel ────────────────────────────────────────────────────────────
const slides  = document.querySelectorAll('.slide');
const dots    = document.querySelectorAll('.dot');
let current   = 0;
let heroTimer;

function goToSlide(index) {
    slides[current].classList.replace('opacity-100', 'opacity-0');
    dots[current].classList.replace('bg-white', 'bg-white/40');
    current = index;
    slides[current].classList.replace('opacity-0', 'opacity-100');
    dots[current].classList.replace('bg-white/40', 'bg-white');
    clearInterval(heroTimer);
    startHeroTimer();
}
window.goToSlide = goToSlide;

function startHeroTimer() {
    heroTimer = setInterval(() => goToSlide((current + 1) % slides.length), 4000);
}

if (slides.length > 0) startHeroTimer();

document.addEventListener('DOMContentLoaded', function () {
    window.initCardTouch();
    bindPaginationLinks();
});

// ─── Skeleton ────────────────────────────────────────────────────────────────
function productSkeletonHTML(count = 8) {
    const card = `
        <div class="skeleton-card">
            <div class="skeleton-img skeleton-pulse"></div>
            <div class="skeleton-line skeleton-pulse" style="width:45%;height:10px;margin-top:10px"></div>
            <div class="skeleton-line skeleton-pulse" style="width:80%;height:13px;margin-top:6px"></div>
            <div class="skeleton-line skeleton-pulse" style="width:55%;height:12px;margin-top:6px"></div>
        </div>`;
    return `<div class="skeleton-grid">${card.repeat(count)}</div>`;
}

// ─── Product Search & Filter ──────────────────────────────────────────────────
let searchTimer;
let activeCategory = 'semua';
let activePage = 1;

window.fetchProducts = function (page = 1) {
    activePage = page;
    const search = document.getElementById('search-input')?.value ?? '';
    document.getElementById('reset-filter')?.classList.toggle('hidden', !search && activeCategory === 'semua');

    clearTimeout(searchTimer);

    document.getElementById('product-grid').innerHTML = productSkeletonHTML(12);

    searchTimer = setTimeout(async () => {
        const params = new URLSearchParams();
        if (search) params.set('search', search);
        if (activeCategory && activeCategory !== 'semua') params.set('category', activeCategory);
        if (activePage > 1) params.set('page', activePage);

        const res  = await fetch(`/shop/search?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        const html = await res.text();
        document.getElementById('product-grid').innerHTML = html;

        bindPaginationLinks();
        window.initCardTouch();

        document.getElementById('products')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 300);
};

function bindPaginationLinks() {
    document.querySelectorAll('.pagination-link').forEach(btn => {
        btn.addEventListener('click', () => {
            window.fetchProducts(parseInt(btn.dataset.page));
        });
    });
}

function setPillActive(pill, isActive) {
    const dark = pill.closest('[data-pill-mode="dark"]') !== null;
    if (dark) {
        pill.classList.toggle('bg-white', isActive);
        pill.classList.toggle('text-gray-900', isActive);
        pill.classList.toggle('border-white', isActive);
        pill.classList.toggle('bg-transparent', !isActive);
        pill.classList.toggle('text-white/70', !isActive);
        pill.classList.toggle('border-white/30', !isActive);
    } else {
        pill.classList.toggle('bg-gray-900', isActive);
        pill.classList.toggle('text-white', isActive);
        pill.classList.toggle('border-gray-900', isActive);
        pill.classList.toggle('bg-white', !isActive);
        pill.classList.toggle('text-gray-600', !isActive);
        pill.classList.toggle('border-gray-200', !isActive);
    }
}

window.filterCategory = function (slug) {
    activeCategory = slug;

    document.querySelectorAll('.category-pill').forEach(pill => {
        setPillActive(pill, pill.dataset.slug === slug);
    });

    document.getElementById('products')?.scrollIntoView({ behavior: 'smooth', block: 'start' });

    window.fetchProducts();
};

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('search-input')?.addEventListener('input', window.fetchProducts);
    document.getElementById('reset-filter')?.addEventListener('click', function () {
        document.getElementById('search-input').value = '';
        activeCategory = 'semua';
        document.querySelectorAll('.category-pill').forEach(pill => {
            setPillActive(pill, pill.dataset.slug === 'semua');
        });
        window.fetchProducts();
    });
});
