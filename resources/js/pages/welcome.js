// ====== SCROLL REVEAL ======
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.08 });

document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale').forEach(el => {
    revealObserver.observe(el);
});

// ====== NUMBER COUNTER ======
function animateCounter(el) {
    const raw    = el.dataset.target;
    const target = parseFloat(raw.replace(/[^0-9.]/g, ''));
    const suffix = raw.replace(/[0-9.,\s]/g, '').trim();
    const dur    = 1800;
    const start  = performance.now();

    (function step(now) {
        const p    = Math.min((now - start) / dur, 1);
        const ease = p === 1 ? 1 : 1 - Math.pow(2, -10 * p);
        el.textContent = Math.floor(ease * target).toLocaleString('id-ID') + suffix;
        if (p < 1) requestAnimationFrame(step);
    })(start);
}

const counterObs = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animateCounter(entry.target);
            counterObs.unobserve(entry.target);
        }
    });
}, { threshold: 0.4 });

document.querySelectorAll('[data-counter]').forEach(el => counterObs.observe(el));

// ====== FLASH SALE COUNTDOWN ======
const cdEl = document.getElementById('flash-countdown');
if (cdEl) {
    const endsAt = parseInt(cdEl.dataset.ends) * 1000;
    const hEl    = document.getElementById('cd-hours');
    const mEl    = document.getElementById('cd-minutes');
    const sEl    = document.getElementById('cd-seconds');
    const pad    = n => String(n).padStart(2, '0');

    function tick() {
        const diff = endsAt - Date.now();
        if (diff <= 0) { cdEl.closest('section')?.remove(); return; }
        if (hEl) hEl.textContent = pad(Math.floor(diff / 3600000));
        if (mEl) mEl.textContent = pad(Math.floor((diff % 3600000) / 60000));
        if (sEl) sEl.textContent = pad(Math.floor((diff % 60000) / 1000));
    }
    tick();
    setInterval(tick, 1000);
}

// ====== HERO PARALLAX (desktop only) ======
const parallaxImg = document.querySelector('.parallax-img');
if (parallaxImg && window.innerWidth >= 768) {
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(() => {
                parallaxImg.style.transform = `translateY(${window.scrollY * 0.15}px)`;
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
}
