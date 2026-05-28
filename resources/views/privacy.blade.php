<x-app-layout>

@push('seo')
<title>{{ $page?->meta_title ?? 'Kebijakan Privasi — Quro Collection' }}</title>
<meta name="description" content="{{ $page?->meta_description ?? 'Kebijakan privasi Quro Collection — bagaimana kami mengumpulkan, menggunakan, dan melindungi data pribadi Anda.' }}">
<meta property="og:title" content="{{ $page?->meta_title ?? 'Kebijakan Privasi — Quro Collection' }}">
<meta property="og:description" content="{{ $page?->meta_description ?? 'Kebijakan privasi Quro Collection — bagaimana kami mengumpulkan, menggunakan, dan melindungi data pribadi Anda.' }}">
<meta property="og:url" content="{{ route('privacy') }}">
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
        <h1 class="font-serif text-4xl md:text-5xl font-semibold text-white mb-4">Kebijakan Privasi</h1>
        <p class="text-zinc-400 text-sm leading-relaxed max-w-xl mx-auto">
            Kami berkomitmen menjaga kepercayaan Anda. Pelajari bagaimana kami mengumpulkan, menggunakan, dan melindungi data pribadi Anda.
        </p>
        <p class="mt-6 text-xs text-zinc-600">Terakhir diperbarui: April 2026</p>
    </div>
</section>

{{-- Content --}}
<section class="bg-gray-950 pb-24">
    <div class="max-w-3xl mx-auto px-6">

        {{-- Divider --}}
        <div class="w-12 h-px bg-zinc-800 mx-auto mb-16"></div>

        {{-- Sections --}}
        <div class="reveal">
            @if($page?->content)
            <div class="rich-content">
                {!! $page->content !!}
            </div>
            @else
            {{-- Fallback static content --}}
            <div class="space-y-14">
            <div class="reveal stagger-1">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-semibold text-zinc-600 tracking-widest uppercase">01</span>
                    <div class="flex-1 h-px bg-zinc-800"></div>
                </div>
                <h2 class="font-serif text-xl font-semibold text-white mb-3">Informasi yang Kami Kumpulkan</h2>
                <p class="text-zinc-400 text-sm leading-relaxed mb-4">
                    Saat Anda menggunakan layanan Quro Collection, kami dapat mengumpulkan informasi berikut:
                </p>
                <ul class="space-y-2 text-sm text-zinc-400">
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span><strong class="text-zinc-300">Data identitas</strong> — nama lengkap dan alamat email yang Anda daftarkan.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span><strong class="text-zinc-300">Data pengiriman</strong> — alamat lengkap, nomor telepon, dan informasi wilayah untuk keperluan pengiriman pesanan.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span><strong class="text-zinc-300">Data transaksi</strong> — riwayat pesanan, metode pembayaran (tidak termasuk detail kartu), dan status pembayaran.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span><strong class="text-zinc-300">Data teknis</strong> — alamat IP, jenis perangkat, dan browser yang Anda gunakan saat mengakses situs.</span>
                    </li>
                </ul>
            </div>

            {{-- 2 --}}
            <div class="reveal stagger-2">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-semibold text-zinc-600 tracking-widest uppercase">02</span>
                    <div class="flex-1 h-px bg-zinc-800"></div>
                </div>
                <h2 class="font-serif text-xl font-semibold text-white mb-3">Bagaimana Kami Menggunakan Data Anda</h2>
                <p class="text-zinc-400 text-sm leading-relaxed mb-4">Data yang kami kumpulkan digunakan untuk tujuan berikut:</p>
                <ul class="space-y-2 text-sm text-zinc-400">
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Memproses dan memenuhi pesanan Anda, termasuk pengiriman dan konfirmasi pembayaran.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Mengirimkan notifikasi penting terkait status pesanan melalui email.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Meningkatkan pengalaman berbelanja dan performa situs.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Mencegah penipuan dan memastikan keamanan transaksi.</span>
                    </li>
                </ul>
            </div>

            {{-- 3 --}}
            <div class="reveal stagger-3">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-semibold text-zinc-600 tracking-widest uppercase">03</span>
                    <div class="flex-1 h-px bg-zinc-800"></div>
                </div>
                <h2 class="font-serif text-xl font-semibold text-white mb-3">Pembagian Data kepada Pihak Ketiga</h2>
                <p class="text-zinc-400 text-sm leading-relaxed mb-4">
                    Kami tidak menjual data pribadi Anda. Data hanya dibagikan kepada pihak ketiga yang diperlukan untuk operasional layanan:
                </p>
                <ul class="space-y-2 text-sm text-zinc-400">
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span><strong class="text-zinc-300">Midtrans</strong> — sebagai payment gateway untuk memproses transaksi secara aman.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span><strong class="text-zinc-300">Jasa pengiriman</strong> — nama dan alamat penerima diberikan kepada kurir untuk keperluan pengiriman paket.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Pihak lain yang diwajibkan oleh hukum atau peraturan yang berlaku.</span>
                    </li>
                </ul>
            </div>

            {{-- 4 --}}
            <div class="reveal stagger-4">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-semibold text-zinc-600 tracking-widest uppercase">04</span>
                    <div class="flex-1 h-px bg-zinc-800"></div>
                </div>
                <h2 class="font-serif text-xl font-semibold text-white mb-3">Keamanan Data</h2>
                <p class="text-zinc-400 text-sm leading-relaxed">
                    Kami menerapkan langkah-langkah keamanan teknis yang wajar, termasuk enkripsi SSL/HTTPS untuk seluruh lalu lintas data dan penyimpanan sandi yang di-hash. Meskipun demikian, tidak ada sistem yang sepenuhnya bebas risiko — kami menganjurkan Anda untuk menjaga kerahasiaan kredensial akun Anda.
                </p>
            </div>

            {{-- 5 --}}
            <div class="reveal stagger-5">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-semibold text-zinc-600 tracking-widest uppercase">05</span>
                    <div class="flex-1 h-px bg-zinc-800"></div>
                </div>
                <h2 class="font-serif text-xl font-semibold text-white mb-3">Cookie</h2>
                <p class="text-zinc-400 text-sm leading-relaxed">
                    Situs kami menggunakan cookie sesi untuk keperluan autentikasi dan preferensi pengguna. Cookie tidak digunakan untuk pelacakan pihak ketiga atau iklan. Anda dapat menonaktifkan cookie melalui pengaturan browser, namun beberapa fitur situs mungkin tidak berfungsi optimal.
                </p>
            </div>

            {{-- 6 --}}
            <div class="reveal">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-semibold text-zinc-600 tracking-widest uppercase">06</span>
                    <div class="flex-1 h-px bg-zinc-800"></div>
                </div>
                <h2 class="font-serif text-xl font-semibold text-white mb-3">Hak Anda</h2>
                <p class="text-zinc-400 text-sm leading-relaxed mb-4">Sebagai pengguna, Anda berhak untuk:</p>
                <ul class="space-y-2 text-sm text-zinc-400">
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Mengakses dan memperbarui data pribadi Anda melalui halaman profil.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Meminta penghapusan akun dan data terkait dengan menghubungi kami.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1.5 w-1 h-1 rounded-full bg-zinc-600 flex-shrink-0"></span>
                        <span>Mengajukan pertanyaan atau keberatan mengenai penggunaan data Anda.</span>
                    </li>
                </ul>
            </div>

            {{-- 7 --}}
            <div class="reveal">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-semibold text-zinc-600 tracking-widest uppercase">07</span>
                    <div class="flex-1 h-px bg-zinc-800"></div>
                </div>
                <h2 class="font-serif text-xl font-semibold text-white mb-3">Perubahan Kebijakan</h2>
                <p class="text-zinc-400 text-sm leading-relaxed">
                    Kami dapat memperbarui kebijakan privasi ini dari waktu ke waktu. Setiap perubahan signifikan akan kami informasikan melalui email atau notifikasi di situs. Dengan terus menggunakan layanan kami setelah perubahan berlaku, Anda dianggap menyetujui kebijakan yang diperbarui.
                </p>
            </div>
            </div>{{-- end .space-y-14 --}}
            @endif
        </div>

        {{-- Contact CTA --}}
        <div class="reveal mt-16 border border-zinc-800 rounded-2xl p-8 text-center">
            <h3 class="font-serif text-lg font-semibold text-white mb-2">Ada Pertanyaan?</h3>
            <p class="text-zinc-400 text-sm mb-6">
                Hubungi kami jika Anda memiliki pertanyaan tentang kebijakan privasi atau data pribadi Anda.
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
