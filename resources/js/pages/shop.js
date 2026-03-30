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

// ─── Product Card Slider ──────────────────────────────────────────────────────
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

// Auto-slide — product IDs injected from blade via data-auto-slide attribute
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-auto-slide]').forEach(el => {
        const productId = el.dataset.autoSlide;
        setInterval(() => window.slideCard(productId, 1), 4000);
    });
});

// ─── Product Search ───────────────────────────────────────────────────────────
let searchTimer;

window.fetchProducts = function () {
    const search = document.getElementById('search-input').value;
    document.getElementById('reset-filter').classList.toggle('hidden', !search);

    clearTimeout(searchTimer);
    searchTimer = setTimeout(async () => {
        const params = new URLSearchParams();
        if (search) params.set('search', search);

        const res  = await fetch(`/shop/search?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        const html = await res.text();
        document.getElementById('product-grid').innerHTML = html;
    }, 200);
};

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('search-input')?.addEventListener('input', window.fetchProducts);
    document.getElementById('reset-filter')?.addEventListener('click', function () {
        document.getElementById('search-input').value = '';
        window.fetchProducts();
    });
});
