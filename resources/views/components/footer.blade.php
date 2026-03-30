<footer class="mt-24">

    {{-- CTA Banner --}}
    @php
        $footerSlides = [
            ['type' => 'image', 'src' => asset('images/produk.jpeg')],
            ['type' => 'video', 'src' => asset('videos/produk.mp4')],
            ['type' => 'image', 'src' => asset('images/produk1.JPEG')],
            ['type' => 'video', 'src' => asset('videos/produk1.mp4')],
            ['type' => 'image', 'src' => asset('images/produk2.JPEG')],
            ['type' => 'video', 'src' => asset('videos/produk2.mp4')],
            ['type' => 'image', 'src' => asset('images/produk3.JPEG')],
        ];
    @endphp

    <div class="relative overflow-hidden bg-gray-900"
        x-data="{
            current: 0,
            total: {{ count($footerSlides) }},
            timer: null,
            next() {
                this.current = (this.current + 1) % this.total;
                this.$nextTick(() => this.handleSlide());
            },
            handleSlide() {
                clearTimeout(this.timer);
                const vid = this.$refs['slide_vid_' + this.current];
                if (vid) {
                    vid.currentTime = 0;
                    vid.play().catch(() => {});
                    vid.onended = () => this.next();
                } else {
                    this.timer = setTimeout(() => this.next(), 4500);
                }
            }
        }"
        x-init="handleSlide()">

        {{-- Static Slides --}}
        @foreach($footerSlides as $i => $slide)
            <div class="absolute inset-0 transition-opacity duration-700"
                :class="current === {{ $i }} ? 'opacity-100 z-10' : 'opacity-0 z-0'">
                @if($slide['type'] === 'image')
                    <img src="{{ $slide['src'] }}" class="w-full h-full object-cover" alt="">
                @else
                    <video x-ref="slide_vid_{{ $i }}"
                        src="{{ $slide['src'] }}"
                        class="w-full h-full object-cover"
                        muted playsinline preload="metadata"></video>
                @endif
            </div>
        @endforeach

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/85 via-gray-900/55 to-gray-900/20 z-20"></div>

        {{-- Dots --}}
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5 z-30">
            @foreach($footerSlides as $i => $slide)
                <button @click="current = {{ $i }}; handleSlide()"
                    class="h-1 rounded-full transition-all duration-300"
                    :class="current === {{ $i }} ? 'bg-white w-5' : 'bg-white/40 w-2'"></button>
            @endforeach
        </div>

        {{-- Content --}}
        <div class="relative z-30 max-w-6xl mx-auto px-6 py-14 flex flex-col md:flex-row items-center justify-between gap-8">
            <div>
                <p class="text-xs tracking-widest uppercase text-gray-300 mb-2">Quro Collection</p>
                <h3 style="font-family: 'Playfair Display', serif;"
                    class="text-3xl md:text-4xl font-semibold text-white leading-snug">
                    Tampil percaya diri<br class="hidden md:block"> setiap hari.
                </h3>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('shop.index') }}"
                    class="bg-white text-gray-900 text-sm font-medium px-6 py-3 rounded-xl hover:bg-gray-100 transition">
                    Belanja Sekarang
                </a>
                <a href="https://wa.me/6283108267397" target="_blank"
                    class="border border-white/40 text-white text-sm px-6 py-3 rounded-xl hover:border-white hover:bg-white/10 transition">
                    WhatsApp
                </a>
            </div>
        </div>

    </div>

    {{-- Main Footer --}}
    <div class="bg-gray-950">
        <div class="max-w-6xl mx-auto px-6 pt-14 pb-6">

            {{-- Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 pb-12 border-b border-gray-800">

                {{-- Brand --}}
                <div class="md:col-span-5">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-5 group w-fit">
                        <img src="{{ asset('images/logo.png') }}" alt="Quro Collection"
                            class="h-8 w-auto opacity-90 group-hover:opacity-100 transition brightness-0 invert">
                        <span style="font-family: 'Playfair Display', serif;"
                            class="text-lg font-semibold text-white tracking-wide">
                            Quro Collection
                        </span>
                    </a>
                    <p class="text-sm text-gray-400 leading-relaxed mb-7 max-w-xs">
                        Fashion muslim modern yang memadukan gaya dan kenyamanan untuk menemani setiap langkahmu.
                    </p>

                    {{-- Social --}}
                    <div class="flex gap-2.5">
                        <a href="https://www.instagram.com/quro.collection" target="_blank" title="Instagram"
                            class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-white flex items-center justify-center text-gray-400 hover:text-gray-900 transition group">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="https://www.tiktok.com/@quro.collection" target="_blank" title="TikTok"
                            class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-white flex items-center justify-center text-gray-400 hover:text-gray-900 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.27 8.27 0 004.84 1.55V6.79a4.85 4.85 0 01-1.07-.1z"/>
                            </svg>
                        </a>
                        <a href="https://wa.me/6283108267397" target="_blank" title="WhatsApp"
                            class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-white flex items-center justify-center text-gray-400 hover:text-gray-900 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.118 1.528 5.855L.057 24l6.302-1.651A11.934 11.934 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.006-1.371l-.36-.214-3.732.978.995-3.636-.235-.374A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/>
                            </svg>
                        </a>
                        <a href="mailto:admin@qurocollection.com" title="Email"
                            class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-white flex items-center justify-center text-gray-400 hover:text-gray-900 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Nav Links --}}
                <div class="md:col-span-3">
                    <p class="text-xs tracking-widest uppercase text-gray-500 mb-5">Menu</p>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white transition">Beranda</a></li>
                        <li><a href="{{ route('shop.index') }}" class="text-sm text-gray-400 hover:text-white transition">Shop</a></li>
                        <li><a href="{{ route('about') }}" class="text-sm text-gray-400 hover:text-white transition">Tentang Kami</a></li>
                        @auth
                            <li><a href="{{ route('cart.index') }}" class="text-sm text-gray-400 hover:text-white transition">Keranjang</a></li>
                            <li><a href="{{ route('orders.index') }}" class="text-sm text-gray-400 hover:text-white transition">Pesanan Saya</a></li>
                            <li><a href="{{ route('wishlist.index') }}" class="text-sm text-gray-400 hover:text-white transition">Wishlist</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition">Masuk</a></li>
                            <li><a href="{{ route('register') }}" class="text-sm text-gray-400 hover:text-white transition">Daftar</a></li>
                        @endauth
                    </ul>
                </div>

                {{-- Info --}}
                <div class="md:col-span-4">
                    <p class="text-xs tracking-widest uppercase text-gray-500 mb-5">Info</p>
                    <ul class="space-y-5">
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Jam Operasional</p>
                                <p class="text-sm text-gray-300">Senin – Sabtu: 08.00 – 21.00</p>
                                <p class="text-sm text-gray-300">Minggu: 09.00 – 18.00</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Lokasi</p>
                                <a href="https://maps.app.goo.gl/Z3Sv3pnZJX1NZ2UX9" target="_blank"
                                    class="text-sm text-gray-300 hover:text-white transition">
                                    Quro Collection Store →
                                </a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Kontak</p>
                                <a href="https://wa.me/6283108267397" target="_blank"
                                    class="text-sm text-gray-300 hover:text-white transition block">+62 831-0826-7397</a>
                                <a href="mailto:admin@qurocollection.com"
                                    class="text-sm text-gray-300 hover:text-white transition block">admin@qurocollection.com</a>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>

            {{-- Payment + Bottom --}}
            <div class="pt-6 flex flex-col md:flex-row items-center justify-between gap-5">

                {{-- Payment --}}
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs text-gray-600 mr-1">Pembayaran:</span>
                    @foreach([
                        ['label' => 'QRIS', 'color' => 'bg-white'],
                        ['label' => 'GoPay', 'color' => 'bg-green-500'],
                        ['label' => 'OVO', 'color' => 'bg-purple-500'],
                        ['label' => 'Dana', 'color' => 'bg-blue-400'],
                        ['label' => 'ShopeePay', 'color' => 'bg-orange-500'],
                        ['label' => 'VA', 'color' => 'bg-blue-600'],
                        ['label' => 'Kartu Kredit', 'color' => 'bg-gray-400'],
                    ] as $m)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-800 text-xs text-gray-400">
                            <span class="w-1.5 h-1.5 rounded-full {{ $m['color'] }}"></span>
                            {{ $m['label'] }}
                        </span>
                    @endforeach
                </div>

                {{-- Copyright --}}
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Midtrans
                    </div>
                    <p class="text-xs text-gray-600">
                        © {{ date('Y') }} <span style="font-family: 'Playfair Display', serif;" class="text-gray-500">Quro Collection</span>
                    </p>
                </div>

            </div>

        </div>
    </div>

</footer>
