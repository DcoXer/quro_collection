// Config injected from blade via window.CheckoutConfig
const cfg  = window.CheckoutConfig;
const csrf = document.querySelector('meta[name="csrf-token"]').content;

let shippingCost = 0;
const baseTotal  = cfg.baseTotal;

// ─── Helpers ────────────────────────────────────────────────────────────────
function setSelectLoading(id, isLoading, placeholder = 'Pilih...') {
    const select = document.getElementById(id);
    if (isLoading) {
        select.disabled  = true;
        select.innerHTML = '<option value="">Memuat data...</option>';
    } else {
        select.disabled  = false;
        select.innerHTML = `<option value="">${placeholder}</option>`;
    }
}

function resetSelect(id) {
    const select     = document.getElementById(id);
    select.innerHTML = '<option value="">Pilih...</option>';
    select.disabled  = true;
}

function resetCourier() {
    document.getElementById('ongkir-result').classList.add('hidden');
    document.getElementById('ongkir-placeholder')?.classList.remove('hidden');
    shippingCost = 0;
    const costEl = document.getElementById('shipping-cost');
    if (costEl) costEl.value = 0;
    document.getElementById('ongkir-row')?.classList.add('hidden');
    document.getElementById('final-total').textContent = 'Rp ' + baseTotal.toLocaleString('id-ID');
}

// ─── Load Provinsi ───────────────────────────────────────────────────────────
async function loadProvinsi() {
    const select     = document.getElementById('select-provinsi');
    select.disabled  = true;
    select.innerHTML = '<option value="">Memuat provinsi...</option>';

    const res  = await fetch(cfg.urls.provinsi);
    const data = await res.json();

    select.disabled  = false;
    select.innerHTML = '<option value="">Pilih Provinsi</option>';
    if (data.is_success) {
        data.data.forEach(p => {
            select.innerHTML += `<option value="${p.code}">${p.name}</option>`;
        });
    }
}

// ─── Cascade Listeners ───────────────────────────────────────────────────────
document.getElementById('select-provinsi').addEventListener('change', async function () {
    const code = this.value;
    document.getElementById('province-name').value = this.options[this.selectedIndex].text;
    resetSelect('select-kabupaten');
    resetSelect('select-kecamatan');
    resetSelect('select-kelurahan');
    resetCourier();
    if (!code) return;

    setSelectLoading('select-kabupaten', true);
    const res  = await fetch(`${cfg.urls.kabupaten}/${code}`);
    const data = await res.json();
    setSelectLoading('select-kabupaten', false, 'Pilih Kabupaten/Kota');
    if (data.is_success) {
        const select = document.getElementById('select-kabupaten');
        data.data.forEach(k => { select.innerHTML += `<option value="${k.code}">${k.name}</option>`; });
    }
});

document.getElementById('select-kabupaten').addEventListener('change', async function () {
    const code = this.value;
    document.getElementById('city-name').value = this.options[this.selectedIndex].text;
    resetSelect('select-kecamatan');
    resetSelect('select-kelurahan');
    resetCourier();
    if (!code) return;

    setSelectLoading('select-kecamatan', true);
    const res  = await fetch(`${cfg.urls.kecamatan}/${code}`);
    const data = await res.json();
    setSelectLoading('select-kecamatan', false, 'Pilih Kecamatan');
    if (data.is_success) {
        const select = document.getElementById('select-kecamatan');
        data.data.forEach(k => { select.innerHTML += `<option value="${k.code}">${k.name}</option>`; });
    }
});

document.getElementById('select-kecamatan').addEventListener('change', async function () {
    const code = this.value;
    document.getElementById('district-name').value = this.options[this.selectedIndex].text;
    resetSelect('select-kelurahan');
    resetCourier();
    if (!code) return;

    setSelectLoading('select-kelurahan', true);
    const res  = await fetch(`${cfg.urls.kelurahan}/${code}`);
    const data = await res.json();
    setSelectLoading('select-kelurahan', false, 'Pilih Kelurahan');
    if (data.is_success) {
        const select = document.getElementById('select-kelurahan');
        data.data.forEach(k => { select.innerHTML += `<option value="${k.code}">${k.name}</option>`; });
    }
});

document.getElementById('select-kelurahan').addEventListener('change', async function () {
    const code = this.value;
    document.getElementById('village-name').value = this.options[this.selectedIndex].text;
    if (!code) return;
    loadOngkir(code);
});

// ─── Ongkir ──────────────────────────────────────────────────────────────────
async function loadOngkir(villageCode) {
    document.getElementById('ongkir-placeholder')?.classList.add('hidden');
    document.getElementById('ongkir-result').classList.remove('hidden');
    document.getElementById('ongkir-list').innerHTML =
        '<p class="text-xs text-gray-400 py-2">Mengecek ongkos kirim...</p>';

    const res = await fetch(cfg.urls.ongkir, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ destination_village_code: villageCode, weight: cfg.weight }),
    });

    const data = await res.json();

    if (!data.is_success || !data.data?.couriers?.length) {
        document.getElementById('ongkir-list').innerHTML =
            '<p class="text-xs text-red-400">Gagal mengecek ongkos kirim.</p>';
        return;
    }

    const jne = data.data.couriers.find(c =>
        c.courier_name?.toLowerCase().includes('jne') || c.courier_code?.toLowerCase().includes('jne')
    );

    if (!jne) {
        document.getElementById('ongkir-list').innerHTML =
            '<p class="text-xs text-red-400">Layanan JNE tidak tersedia untuk wilayah ini.</p>';
        return;
    }

    shippingCost = parseInt(jne.price);
    document.getElementById('shipping-cost').value    = shippingCost;
    document.getElementById('courier-service').value  = `JNE Express${jne.estimation ? ' - ' + jne.estimation : ''}`;
    document.getElementById('ongkir-row').classList.remove('hidden');
    document.getElementById('ongkir-amount').textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
    document.getElementById('final-total').textContent   = 'Rp ' + (baseTotal + shippingCost).toLocaleString('id-ID');

    document.getElementById('ongkir-list').innerHTML = `
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;border:1.5px solid #111827;border-radius:12px;background:#f9fafb;">
            <div>
                <p style="font-size:14px;font-weight:600;color:#111827;margin:0;">JNE Express</p>
                <p style="font-size:12px;color:#9ca3af;margin:2px 0 0;">${jne.estimation ?? 'Estimasi tidak tersedia'}</p>
            </div>
            <span style="font-size:14px;font-weight:600;color:#111827;">Rp ${shippingCost.toLocaleString('id-ID')}</span>
        </div>
    `;
}

// ─── Voucher ─────────────────────────────────────────────────────────────────
window.applyVoucher = async function () {
    const code = document.getElementById('voucher-input').value.trim();
    if (!code) { window.showToast?.('Masukkan kode voucher dulu', 'error'); return; }

    const res  = await fetch(cfg.urls.voucherApply, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ code }),
    });
    const data = await res.json();

    if (data.success) {
        window.showToast?.(data.message, 'success');
        setTimeout(() => location.reload(), 800);
    } else {
        window.showToast?.(data.message, 'error');
    }
};

window.removeVoucher = async function () {
    await fetch(cfg.urls.voucherRemove, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    });
    location.reload();
};

// ─── Init ─────────────────────────────────────────────────────────────────────
loadProvinsi();
