<x-app-layout>

@push('seo')
<title>Syarat & Ketentuan — Quro Collection</title>
<meta name="description" content="Syarat dan ketentuan penggunaan layanan Quro Collection — hak, kewajiban, dan aturan yang berlaku bagi seluruh pengguna.">
@endpush

@push('styles')
<style>
    .reveal {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }
    .reveal.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .stagger-1 { transition-delay: 0.05s; }
    .stagger-2 { transition-delay: 0.10s; }
    .stagger-3 { transition-delay: 0.15s; }
    .stagger-4 { transition-delay: 0.20s; }
    .stagger-5 { transition-delay: 0.25s; }
    /* Rich content styling */
    .rich-content h2 { font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 600; color: #fff; margin-bottom: 0.75rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #27272a; }
    .rich-content h2:first-child { margin-top: 0; padding-top: 0; border-top: none; }
    .rich-content h3 { font-size: 1rem; font-weight: 600; color: #e4e4e7; margin-bottom: 0.5rem; margin-top: 1.5rem; }
    .rich-content p { color: #a1a1aa; font-size: 0.875rem; line-height: 1.75; margin-bottom: 0.75rem; }
    .rich-content ul, .rich-content ol { color: #a1a1aa; font-size: 0.875rem; padding-left: 1.25rem; margin-bottom: 0.75rem; }
    .rich-content ul { list-style-type: disc; }
    .rich-content ol { list-style-type: decimal; }
    .rich-content li { margin-bottom: 0.4rem; line-height: 1.7; }
    .rich-content strong { color: #e4e4e7; font-weight: 600; }
    .rich-content a { color: #a1a1aa; text-decoration: underline; }
</style>
@endpush

{{-- Hero --}}
<section class="relative bg-gray-950 overflow-hidden">
    <div class="absolute inset-0 bg-[url('/images/produk.jpeg')] bg-center bg-cover opacity-[0.06]"></div>
    <div class="relative max-w-3xl mx-auto px-6 py-24 text-center">
        <p class="text-xs font-semibold tracking-[4px] uppercase text-zinc-500 mb-4">Legal</p>
        <h1 class="font-serif text-4xl md:text-5xl font-semibold text-white mb-4">Syarat &amp; Ketentuan</h1>
        <p class="text-zinc-400 text-sm leading-relaxed max-w-xl mx-auto">
            Dengan menggunakan layanan Quro Collection, Anda menyetujui syarat dan ketentuan berikut. Harap baca dengan seksama sebelum berbelanja.
        </p>
        <p class="mt-6 text-xs text-zinc-600">Terakhir diperbarui: April 2026</p>
    </div>
</section>

{{-- Content --}}
<section class="bg-gray-950 pb-24">
    <div class="max-w-3xl mx-auto px-6">

        <div class="w-12 h-px bg-zinc-800 mx-auto mb-16"></div>

        <div class="page-content reveal">
            @if($page?->content)
            <div class="rich-content">
                {!! $page->content !!}
            </div>
            @else
            {{-- Fallback static content --}}
            <div class="reveal stagger-1">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-semibold text-zinc-600 tracking-widest uppercase">01</span>
                    <div class="flex-1 h-px bg-zinc-800"></div>
                </div>
                <h2 class="font-serif text-xl font-semibold text-white mb-3">Penerimaan Syarat</h2>
                <p class="text-zinc-400 text-sm leading-relaxed">
                    Dengan mengakses dan menggunakan situs Quro Collection, Anda menyatakan telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan ini. Jika Anda tidak menyetujui, harap hentikan penggunaan layanan kami. Kami berhak memperbarui syarat ini kapan saja tanpa pemberitahuan sebelumnya.
                </p>
            </div>

            {{-- 2 --}}
            <div class="reveal stagger-2">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-semibold text-zinc-600 tracking-widest uppercase">02</span>
                    <div class="flex-1 h-px bg-zinc-800"></div>
                </div>
                <h2 class="font-serif text-xl font-semibold text-white mb-3">Akun Pengguna</h2>
                <p class="text-zinc-400 text-sm leading-relaxed mb-4">
                    Untuk melakukan pembelian, Anda wajib membuat akun dengan informasi yang benar dan terkini. Anda bertanggung jawab atas:
                </p>
                <ul class="space-y-2 text-sm text-zinc-400">
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Kerahasiaan kata sandi dan keamanan akun Anda.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Seluruh aktivitas yang terjadi di bawah akun Anda.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Segera memberitahu kami jika terdapat akses tidak sah ke akun Anda.</span>
                    </li>
                </ul>
            </div>

            {{-- 3 --}}
            <div class="reveal stagger-3">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-semibold text-zinc-600 tracking-widest uppercase">03</span>
                    <div class="flex-1 h-px bg-zinc-800"></div>
                </div>
                <h2 class="font-serif text-xl font-semibold text-white mb-3">Pemesanan &amp; Pembayaran</h2>
                <p class="text-zinc-400 text-sm leading-relaxed mb-4">
                    Setiap pesanan yang Anda lakukan tunduk pada ketersediaan stok dan konfirmasi pembayaran. Hal-hal yang perlu diperhatikan:
                </p>
                <ul class="space-y-2 text-sm text-zinc-400">
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Harga yang tercantum sudah termasuk pajak dan dapat berubah sewaktu-waktu.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Pembayaran diproses melalui Midtrans dan tunduk pada kebijakan mereka.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Pesanan yang telah dibayar akan diproses dalam 1x24 jam hari kerja.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Kami berhak membatalkan pesanan jika terdapat indikasi penipuan atau stok habis.</span>
                    </li>
                </ul>
            </div>

            {{-- 4 --}}
            <div class="reveal stagger-4">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-semibold text-zinc-600 tracking-widest uppercase">04</span>
                    <div class="flex-1 h-px bg-zinc-800"></div>
                </div>
                <h2 class="font-serif text-xl font-semibold text-white mb-3">Pengiriman</h2>
                <p class="text-zinc-400 text-sm leading-relaxed mb-4">
                    Pengiriman dilakukan melalui jasa kurir pilihan. Estimasi waktu pengiriman bersifat perkiraan dan dapat berbeda tergantung lokasi tujuan dan kondisi di lapangan. Kami tidak bertanggung jawab atas keterlambatan yang disebabkan oleh:
                </p>
                <ul class="space-y-2 text-sm text-zinc-400">
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Bencana alam, cuaca ekstrem, atau kejadian di luar kendali kami.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Kesalahan alamat yang diberikan oleh pembeli.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Keterlambatan dari pihak jasa pengiriman.</span>
                    </li>
                </ul>
            </div>

            {{-- 5 --}}
            <div class="reveal stagger-5">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-semibold text-zinc-600 tracking-widest uppercase">05</span>
                    <div class="flex-1 h-px bg-zinc-800"></div>
                </div>
                <h2 class="font-serif text-xl font-semibold text-white mb-3">Pengembalian &amp; Refund</h2>
                <p class="text-zinc-400 text-sm leading-relaxed mb-4">
                    Pengembalian barang dapat dilakukan dalam kondisi berikut:
                </p>
                <ul class="space-y-2 text-sm text-zinc-400">
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Produk diterima dalam kondisi rusak atau cacat produksi.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Produk yang diterima tidak sesuai dengan pesanan.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Permintaan pengembalian diajukan maksimal 2x24 jam setelah produk diterima.</span>
                    </li>
                </ul>
                <p class="text-zinc-400 text-sm leading-relaxed mt-4">
                    Refund akan diproses dalam 3-7 hari kerja setelah produk retur diterima dan diverifikasi.
                </p>
            </div>

            {{-- 6 --}}
            <div class="reveal">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-semibold text-zinc-600 tracking-widest uppercase">06</span>
                    <div class="flex-1 h-px bg-zinc-800"></div>
                </div>
                <h2 class="font-serif text-xl font-semibold text-white mb-3">Larangan Penggunaan</h2>
                <p class="text-zinc-400 text-sm leading-relaxed mb-4">
                    Anda dilarang menggunakan layanan kami untuk:
                </p>
                <ul class="space-y-2 text-sm text-zinc-400">
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Melakukan pemesanan fiktif atau penipuan dengan identitas palsu.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Mencoba mengakses sistem kami secara tidak sah.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Aktivitas yang melanggar hukum yang berlaku di Indonesia.</span>
                    </li>
                </ul>
                <p class="text-zinc-400 text-sm leading-relaxed mt-4">
                    Pelanggaran terhadap ketentuan ini dapat mengakibatkan penangguhan atau penghapusan akun Anda.
                </p>
            </div>

            {{-- 7 --}}
            <div class="reveal">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-semibold text-zinc-600 tracking-widest uppercase">07</span>
                    <div class="flex-1 h-px bg-zinc-800"></div>
                </div>
                <h2 class="font-serif text-xl font-semibold text-white mb-3">Hukum yang Berlaku</h2>
                <p class="text-zinc-400 text-sm leading-relaxed">
                    Syarat dan ketentuan ini diatur berdasarkan hukum Republik Indonesia. Setiap sengketa yang timbul akan diselesaikan secara musyawarah. Apabila tidak tercapai kesepakatan, penyelesaian akan dilakukan melalui jalur hukum yang berlaku di Indonesia.
                </p>
            </div>
            @endif
        </div>

        {{-- Contact CTA --}}
        <div class="reveal mt-16 border border-zinc-800 rounded-2xl p-8 text-center">
            <h3 class="font-serif text-lg font-semibold text-white mb-2">Ada Pertanyaan?</h3>
            <p class="text-zinc-400 text-sm mb-6">
                Hubungi kami jika Anda memiliki pertanyaan mengenai syarat dan ketentuan ini.
            </p>
            <a href="mailto:qurocollection@gmail.com"
               class="inline-flex items-center gap-2 bg-white text-gray-950 text-sm font-medium px-6 py-3 rounded-xl hover:bg-zinc-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                qurocollection@gmail.com
            </a>
        </div>

    </div>
</section>

@push('scripts')
<script>
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
@endpush

</x-app-layout>
