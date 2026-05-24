// Profile page — cascade dropdown for address fields
const cfg = window.ProfileConfig;

function setLoading(id, isLoading, placeholder = 'Pilih...') {
    const select = document.getElementById(id);
    if (isLoading) {
        select.disabled  = true;
        select.innerHTML = '<option value="">Memuat data...</option>';
    } else {
        select.disabled  = false;
        select.innerHTML = `<option value="">${placeholder}</option>`;
    }
}

function resetSelect(id, placeholder = 'Pilih...') {
    const select     = document.getElementById(id);
    select.innerHTML = `<option value="">${placeholder}</option>`;
    select.disabled  = true;
}

// ─── Load Provinsi ────────────────────────────────────────────────────────────
async function loadProvinsi() {
    const select     = document.getElementById('profile-select-provinsi');
    select.disabled  = true;
    select.innerHTML = '<option value="">Memuat provinsi...</option>';

    const res  = await fetch(cfg.urls.provinsi);
    const data = await res.json();

    select.disabled  = false;
    select.innerHTML = '<option value="">Pilih Provinsi</option>';
    if (data.is_success) {
        data.data.forEach(p => {
            const opt      = document.createElement('option');
            opt.value      = p.code;
            opt.textContent = p.name;
            select.appendChild(opt);
        });
    }

    // Auto-select saved province
    const saved = cfg.savedAddress;
    if (saved.province_id) {
        select.value = saved.province_id;
        if (select.value) {
            await loadKabupaten(saved.province_id, saved);
        }
    }
}

// ─── Load Kabupaten ───────────────────────────────────────────────────────────
async function loadKabupaten(provinceCode, saved = null) {
    setLoading('profile-select-kabupaten', true);
    const res  = await fetch(`${cfg.urls.kabupaten}/${provinceCode}`);
    const data = await res.json();
    setLoading('profile-select-kabupaten', false, 'Pilih Kabupaten/Kota');

    if (data.is_success) {
        const select = document.getElementById('profile-select-kabupaten');
        data.data.forEach(k => {
            const opt      = document.createElement('option');
            opt.value      = k.code;
            opt.textContent = k.name;
            select.appendChild(opt);
        });
    }

    if (saved?.city_id) {
        const select = document.getElementById('profile-select-kabupaten');
        select.value = saved.city_id;
        if (select.value) {
            document.getElementById('profile-city-name').value = saved.city_name;
            await loadKecamatan(saved.city_id, saved);
        }
    }
}

// ─── Load Kecamatan ───────────────────────────────────────────────────────────
async function loadKecamatan(cityCode, saved = null) {
    setLoading('profile-select-kecamatan', true);
    const res  = await fetch(`${cfg.urls.kecamatan}/${cityCode}`);
    const data = await res.json();
    setLoading('profile-select-kecamatan', false, 'Pilih Kecamatan');

    if (data.is_success) {
        const select = document.getElementById('profile-select-kecamatan');
        data.data.forEach(k => {
            const opt      = document.createElement('option');
            opt.value      = k.code;
            opt.textContent = k.name;
            select.appendChild(opt);
        });
    }

    if (saved?.district_id) {
        const select = document.getElementById('profile-select-kecamatan');
        select.value = saved.district_id;
        if (select.value) {
            document.getElementById('profile-district-name').value = saved.district_name;
            await loadKelurahan(saved.district_id, saved);
        }
    }
}

// ─── Load Kelurahan ───────────────────────────────────────────────────────────
async function loadKelurahan(districtCode, saved = null) {
    setLoading('profile-select-kelurahan', true);
    const res  = await fetch(`${cfg.urls.kelurahan}/${districtCode}`);
    const data = await res.json();
    setLoading('profile-select-kelurahan', false, 'Pilih Kelurahan');

    if (data.is_success) {
        const select = document.getElementById('profile-select-kelurahan');
        data.data.forEach(k => {
            const opt      = document.createElement('option');
            opt.value      = k.code;
            opt.textContent = k.name;
            select.appendChild(opt);
        });
    }

    if (saved?.village_id) {
        const select = document.getElementById('profile-select-kelurahan');
        select.value = saved.village_id;
        if (select.value) {
            document.getElementById('profile-village-name').value = saved.village_name;
        }
    }
}

// ─── Cascade Listeners ────────────────────────────────────────────────────────
document.getElementById('profile-select-provinsi').addEventListener('change', async function () {
    const code = this.value;
    document.getElementById('profile-province-name').value = this.options[this.selectedIndex].text === 'Pilih Provinsi' ? '' : this.options[this.selectedIndex].text;
    resetSelect('profile-select-kabupaten', 'Pilih Kabupaten/Kota');
    resetSelect('profile-select-kecamatan', 'Pilih Kecamatan');
    resetSelect('profile-select-kelurahan', 'Pilih Kelurahan');
    document.getElementById('profile-city-name').value     = '';
    document.getElementById('profile-district-name').value = '';
    document.getElementById('profile-village-name').value  = '';
    if (!code) return;
    await loadKabupaten(code);
});

document.getElementById('profile-select-kabupaten').addEventListener('change', async function () {
    const code = this.value;
    document.getElementById('profile-city-name').value = this.options[this.selectedIndex].text === 'Pilih Kabupaten/Kota' ? '' : this.options[this.selectedIndex].text;
    resetSelect('profile-select-kecamatan', 'Pilih Kecamatan');
    resetSelect('profile-select-kelurahan', 'Pilih Kelurahan');
    document.getElementById('profile-district-name').value = '';
    document.getElementById('profile-village-name').value  = '';
    if (!code) return;
    await loadKecamatan(code);
});

document.getElementById('profile-select-kecamatan').addEventListener('change', async function () {
    const code = this.value;
    document.getElementById('profile-district-name').value = this.options[this.selectedIndex].text === 'Pilih Kecamatan' ? '' : this.options[this.selectedIndex].text;
    resetSelect('profile-select-kelurahan', 'Pilih Kelurahan');
    document.getElementById('profile-village-name').value = '';
    if (!code) return;
    await loadKelurahan(code);
});

document.getElementById('profile-select-kelurahan').addEventListener('change', function () {
    const text = this.options[this.selectedIndex].text;
    document.getElementById('profile-village-name').value = text === 'Pilih Kelurahan' ? '' : text;
});

// ─── Init ─────────────────────────────────────────────────────────────────────
loadProvinsi();
