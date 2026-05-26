<x-app-layout>
    @push('seo')
    <title>Checkout — Quro Collection</title>
    <meta name="robots" content="noindex, nofollow">
    @endpush

    @vite(['resources/css/pages/checkout.css'])

    <div class="max-w-6xl mx-auto px-4 py-8 md:py-12">

        {{-- Page Header --}}
        <div class="mb-8">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-1.5">Pembelian</p>
            <h1 style="font-family: 'Playfair Display', serif;" class="text-3xl font-semibold text-gray-900">Checkout</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ───── Kiri — Form ───── --}}
            <div class="lg:col-span-2 space-y-4">
                <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}">
                    @csrf

                    {{-- 1. Info Penerima --}}
                    <div class="checkout-card">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="checkout-step-icon"><span>1</span></div>
                            <h2 class="checkout-card-title">Informasi Penerima</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="checkout-label">Nama Penerima</label>
                                <input type="text" name="shipping_name"
                                    value="{{ old('shipping_name', auth()->user()->name) }}"
                                    class="checkout-input">
                                @error('shipping_name')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="checkout-label">Nomor HP</label>
                                <input type="text" name="shipping_phone"
                                    value="{{ old('shipping_phone', auth()->user()->phone) }}"
                                    placeholder="08xxxxxxxxxx"
                                    class="checkout-input">
                                @error('shipping_phone')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- 2. Alamat --}}
                    <div class="checkout-card">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="checkout-step-icon"><span>2</span></div>
                            <h2 class="checkout-card-title">Alamat Pengiriman</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="checkout-label">Provinsi</label>
                                <select id="select-provinsi" name="province_id" class="checkout-input">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                <input type="hidden" name="province_name" id="province-name">
                            </div>
                            <div>
                                <label class="checkout-label">Kabupaten / Kota</label>
                                <select id="select-kabupaten" name="city_id" disabled class="checkout-input">
                                    <option value="">Pilih Kabupaten/Kota</option>
                                </select>
                                <input type="hidden" name="city_name" id="city-name">
                            </div>
                            <div>
                                <label class="checkout-label">Kecamatan</label>
                                <select id="select-kecamatan" name="district_id" disabled class="checkout-input">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                <input type="hidden" name="district_name" id="district-name">
                            </div>
                            <div>
                                <label class="checkout-label">Kelurahan</label>
                                <select id="select-kelurahan" name="village_id" disabled class="checkout-input">
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                                <input type="hidden" name="village_name" id="village-name">
                            </div>
                            <div class="md:col-span-2">
                                <label class="checkout-label">Alamat Lengkap</label>
                                <textarea name="shipping_address" rows="3"
                                    placeholder="Nama jalan, nomor rumah, RT/RW, patokan..."
                                    class="checkout-input" style="resize: none;">{{ old('shipping_address', auth()->user()->address_detail) }}</textarea>
                                @error('shipping_address')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- 3. Pengiriman --}}
                    <div class="checkout-card">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="checkout-step-icon"><span>3</span></div>
                            <h2 class="checkout-card-title">Estimasi Ongkos Pengiriman</h2>
                        </div>

                        <input type="hidden" name="courier" id="courier-hidden" value="all">
                        <input type="hidden" name="courier_service" id="courier-service">
                        <input type="hidden" name="shipping_cost" id="shipping-cost" value="0">

                        <div id="ongkir-result" class="hidden">
                            <p class="checkout-label mb-3">Estimasi Biaya Pengiriman</p>
                            <div id="ongkir-list" class="space-y-2"></div>
                        </div>

                        <p id="ongkir-placeholder" class="text-sm text-gray-400">
                            Lengkapi alamat hingga kelurahan untuk melihat estimasi ongkir.
                        </p>
                    </div>

                    {{-- 4. Voucher --}}
                    <div class="checkout-card">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="checkout-step-icon"><span>4</span></div>
                            <h2 class="checkout-card-title">
                                Kode Voucher
                                <span class="text-gray-400 font-normal text-xs ml-1">(opsional)</span>
                            </h2>
                        </div>

                        <div id="voucher-applied" class="{{ session('voucher') ? '' : 'hidden' }}">
                            <div class="voucher-applied">
                                <div>
                                    <p id="voucher-code-display" class="text-sm font-semibold text-green-700">{{ session('voucher.code') }}</p>
                                    <p id="voucher-discount-display" class="text-xs text-green-500 mt-0.5">
                                        Hemat Rp {{ number_format(session('voucher.discount', 0), 0, ',', '.') }}
                                    </p>
                                </div>
                                <button type="button" onclick="removeVoucher()"
                                    class="text-green-400 hover:text-red-400 transition text-lg leading-none">✕</button>
                            </div>
                        </div>

                        <div id="voucher-form" class="{{ session('voucher') ? 'hidden' : '' }}">
                            <div class="flex gap-2">
                                <input type="text" id="voucher-input"
                                    placeholder="Masukkan kode voucher"
                                    class="checkout-input uppercase" style="flex: 1;"
                                    onkeydown="if(event.key==='Enter'){event.preventDefault();applyVoucher();}">
                                <button type="button" id="voucher-btn" onclick="applyVoucher()"
                                    class="bg-gray-900 text-white px-5 rounded-xl text-sm font-medium hover:bg-gray-700 transition whitespace-nowrap">
                                    Pakai
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile: Submit --}}
                    <div class="lg:hidden space-y-3">
                        <button id="submit-btn-mobile" type="submit" class="btn-primary" disabled>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Buat Pesanan
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn-secondary">Batalkan</a>
                    </div>

                </form>
            </div>

            {{-- ───── Kanan — Ringkasan ───── --}}
            <div class="lg:col-span-1">
                <div class="summary-card">

                    <p style="font-family: 'Playfair Display', serif;"
                        class="text-lg font-semibold text-gray-900 mb-5">Ringkasan</p>

                    {{-- Items --}}
                    <div class="space-y-3 mb-5">
                        @foreach($cart as $item)
                        <div class="flex gap-3 items-center">
                            <div class="summary-item-thumb">
                                @if($item['image'])
                                    <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-900 truncate">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $item['size'] ?? '-' }} &times; {{ $item['quantity'] }}
                                </p>
                            </div>
                            <p class="text-xs font-semibold text-gray-900 shrink-0">
                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            </p>
                        </div>
                        @endforeach
                    </div>

                    <div class="checkout-divider"></div>

                    {{-- Breakdown harga --}}
                    <div class="space-y-2.5 mb-4">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div id="ongkir-row" class="hidden flex justify-between text-sm text-gray-500">
                            <span id="ongkir-label">Ongkos Kirim</span>
                            <span id="ongkir-amount">Rp 0</span>
                        </div>
                        <div id="discount-row" class="{{ session('voucher') ? '' : 'hidden' }} flex justify-between text-sm text-green-600">
                            <span>Diskon</span>
                            <span id="discount-amount">- Rp {{ number_format(session('voucher.discount', 0), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="checkout-divider"></div>

                    {{-- Total --}}
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-sm font-medium text-gray-700">Total</span>
                        <span style="font-family: 'Playfair Display', serif;"
                            id="final-total"
                            class="text-2xl font-semibold text-gray-900">
                            Rp {{ number_format($total - session('voucher.discount', 0), 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Desktop: Submit --}}
                    <div class="hidden lg:block space-y-3">
                        <button id="submit-btn-desktop" type="submit" form="checkout-form" class="btn-primary" disabled>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Buat Pesanan
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn-secondary">Batalkan</a>
                    </div>

                    <div class="security-note">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Pembayaran aman via Midtrans
                    </div>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        window.CheckoutConfig = {
            baseTotal: {{ $total }},
            initialDiscount: {{ session('voucher.discount', 0) }},
            weight: {{ $weight ?? 0.5 }},
            urls: {
                provinsi:      '{{ route('wilayah.provinsi') }}',
                kabupaten:     '{{ url('wilayah/kabupaten') }}',
                kecamatan:     '{{ url('wilayah/kecamatan') }}',
                kelurahan:     '{{ url('wilayah/kelurahan') }}',
                ongkir:        '{{ route('ongkir.check') }}',
                voucherApply:  '{{ route('voucher.apply') }}',
                voucherRemove: '{{ route('voucher.remove') }}',
            },
            savedAddress: {
                province_id:   '{{ auth()->user()->province_id }}',
                province_name: '{{ auth()->user()->province_name }}',
                city_id:       '{{ auth()->user()->city_id }}',
                city_name:     '{{ auth()->user()->city_name }}',
                district_id:   '{{ auth()->user()->district_id }}',
                district_name: '{{ auth()->user()->district_name }}',
                village_id:    '{{ auth()->user()->village_id }}',
                village_name:  '{{ auth()->user()->village_name }}',
            },
        };
    </script>
    @vite(['resources/js/pages/checkout.js'])
    @endpush
</x-app-layout>