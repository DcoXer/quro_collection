<x-app-layout>

@push('seo')
<title>Tentang Kami — Quro Collection</title>
<meta name="description" content="Quro Collection adalah brand fashion muslim premium yang menghadirkan koleksi baju koko modern dengan bahan berkualitas tinggi.">
@endpush

@push('styles')
<style>
    .reveal {
        opacity: 0;
        transform: translateY(28px);
        transition: opacity 0.65s ease, transform 0.65s ease;
    }
    .reveal.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .reveal-left {
        opacity: 0;
        transform: translateX(-28px);
        transition: opacity 0.65s ease, transform 0.65s ease;
    }
    .reveal-left.visible {
        opacity: 1;
        transform: translateX(0);
    }
    .reveal-right {
        opacity: 0;
        transform: translateX(28px);
        transition: opacity 0.65s ease, transform 0.65s ease;
    }
    .reveal-right.visible {
        opacity: 1;
        transform: translateX(0);
    }
    .delay-100 { transition-delay: 0.1s; }
    .delay-200 { transition-delay: 0.2s; }
    .delay-300 { transition-delay: 0.3s; }
    .delay-400 { transition-delay: 0.4s; }
</style>
@endpush

{{-- ── Hero ── --}}
<section class="relative overflow-hidden bg-gray-900" style="min-height: 70vh;">

    {{-- Background --}}
    <div class="absolute inset-0">
        <img src="{{ asset('images/produk2.JPEG') }}" alt=""
            class="w-full h-full object-cover opacity-40">
    </div>
    <div class="absolute inset-0 bg-gradient-to-r from-gray-900/90 via-gray-900/60 to-transparent"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 via-transparent to-transparent"></div>

    {{-- Content --}}
    <div class="relative z-10 max-w-6xl mx-auto px-6 md:px-16 flex flex-col justify-end pb-16 md:pb-20" style="min-height: 70vh;">
        <div class="max-w-xl">
            <div class="flex items-center gap-2 mb-5">
                <span class="w-6 h-px bg-white/50"></span>
                <p class="text-xs tracking-[0.25em] uppercase text-white/60">Tentang Kami</p>
            </div>
            <h1 style="font-family: 'Playfair Display', serif;"
                class="text-4xl md:text-6xl font-semibold text-white leading-[1.1] mb-5">
                Fashion Muslim<br>
                <em class="font-normal not-italic text-white/75">Modern & Elegan.</em>
            </h1>
            <p class="text-white/55 text-sm md:text-base leading-relaxed max-w-sm">
                Kami hadir untuk menemani kamu tampil percaya diri di setiap momen — dari hari biasa hingga hari spesial.
            </p>
        </div>
    </div>

</section>

{{-- ── Stats Strip ── --}}
<section class="bg-gray-950 border-b border-gray-800">
    <div class="max-w-6xl mx-auto px-6 py-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @foreach([
                ['value' => '2025',                      'label' => 'Tahun Berdiri'],
                ['value' => number_format($totalSold),   'label' => 'Produk Terjual'],
                ['value' => '300+',                      'label' => 'Pelanggan Puas'],
                ['value' => '34',                        'label' => 'Kota Dijangkau'],
            ] as $stat)
            <div class="reveal text-center md:text-left">
                <p style="font-family: 'Playfair Display', serif;"
                    class="text-3xl md:text-4xl font-semibold text-white mb-1">{{ $stat['value'] }}</p>
                <p class="text-xs text-gray-500 tracking-wide uppercase">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Story ── --}}
<section class="max-w-6xl mx-auto px-6 py-20 md:py-28">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">

        {{-- Image --}}
        <div class="reveal-left relative">
            <div class="aspect-[4/5] rounded-2xl overflow-hidden bg-gray-100">
                <img src="{{ asset('images/produk1.JPEG') }}" alt="Quro Collection"
                    class="w-full h-full object-cover">
            </div>
            {{-- floating badge --}}
            <div class="absolute -bottom-5 -right-5 bg-gray-900 text-white rounded-2xl px-5 py-4 shadow-xl">
                <p style="font-family: 'Playfair Display', serif;" class="text-xl font-semibold">{{ now()->year - 2025 }}+</p>
                <p class="text-xs text-gray-400 mt-0.5">Tahun Pengalaman</p>
            </div>
        </div>

        {{-- Text --}}
        <div class="reveal-right">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-6 h-px bg-gray-400"></span>
                <p class="text-xs tracking-widest uppercase text-gray-400">Cerita Kami</p>
            </div>
            <h2 style="font-family: 'Playfair Display', serif;"
                class="text-3xl md:text-4xl font-semibold text-gray-900 leading-tight mb-6">
                {{ $page?->title ?? 'Dibangun dengan passion, dipakai dengan bangga.' }}
            </h2>
            @if($page?->content)
            <div class="text-gray-500 text-sm leading-relaxed mb-8 space-y-4">
                {!! $page->content !!}
            </div>
            @else
            <p class="text-gray-500 leading-relaxed mb-5">
                Quro Collection lahir dari kecintaan terhadap fashion muslim yang modern namun tetap
                menjaga nilai-nilai kesederhanaan dan keanggunan. Kami percaya bahwa tampil rapi
                dan elegan adalah bagian dari cara menghargai diri sendiri.
            </p>
            <p class="text-gray-500 leading-relaxed mb-8">
                Setiap produk kami dirancang dengan teliti, menggunakan bahan pilihan yang nyaman
                dipakai sepanjang hari — dari aktivitas sehari-hari hingga momen spesial bersama keluarga.
            </p>
            @endif
            <a href="{{ route('shop.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-gray-900 border-b border-gray-900 pb-0.5 hover:gap-3 transition-all">
                Lihat Koleksi
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

    </div>
</section>

{{-- ── Values ── --}}
<section class="bg-gray-50 py-20">
    <div class="max-w-6xl mx-auto px-6">

        <div class="reveal text-center mb-14">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-3">Nilai Kami</p>
            <h2 style="font-family: 'Playfair Display', serif;"
                class="text-3xl md:text-4xl font-semibold text-gray-900">
                Mengapa Memilih Quro?
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                [
                    'num'   => '01',
                    'title' => 'Kualitas Terjamin',
                    'desc'  => 'Setiap produk melewati seleksi ketat untuk memastikan kualitas bahan dan jahitan terbaik yang tahan lama.',
                    'icon'  => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
                    'delay' => '',
                ],
                [
                    'num'   => '02',
                    'title' => 'Pengiriman Cepat',
                    'desc'  => 'Pesanan diproses dan dikirim dengan cepat ke seluruh Indonesia melalui kurir terpercaya dengan tracking real-time.',
                    'icon'  => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0',
                    'delay' => 'delay-200',
                ],
                [
                    'num'   => '03',
                    'title' => 'Pelanggan Pertama',
                    'desc'  => 'Kepuasan pelanggan adalah prioritas utama kami. Tim kami siap membantu kamu kapan saja dengan senang hati.',
                    'icon'  => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                    'delay' => 'delay-400',
                ],
            ] as $val)
            <div class="reveal {{ $val['delay'] }} bg-white rounded-2xl p-8 group hover:shadow-md transition-shadow duration-300">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-12 h-12 bg-gray-900 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $val['icon'] }}"/>
                        </svg>
                    </div>
                    <span style="font-family: 'Playfair Display', serif;"
                        class="text-4xl font-semibold text-gray-100 group-hover:text-gray-200 transition-colors">
                        {{ $val['num'] }}
                    </span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2 text-lg">{{ $val['title'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $val['desc'] }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ── Contact ── --}}
<section class="max-w-6xl mx-auto px-6 py-20">

    <div class="reveal text-center mb-12">
        <p class="text-xs tracking-widest uppercase text-gray-400 mb-3">Kontak</p>
        <h2 style="font-family: 'Playfair Display', serif;"
            class="text-3xl md:text-4xl font-semibold text-gray-900">
            Ada Pertanyaan?
        </h2>
        <p class="text-gray-400 text-sm mt-3">Kami siap membantu kamu — pilih cara yang paling mudah</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <a href="https://wa.me/6283108267397" target="_blank"
            class="reveal delay-100 group flex items-center gap-5 border border-gray-100 rounded-2xl p-6 hover:border-gray-900 hover:shadow-sm transition-all duration-300">
            <div class="w-12 h-12 bg-green-50 group-hover:bg-green-100 rounded-xl flex items-center justify-center shrink-0 transition">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 group-hover:text-gray-700 transition">WhatsApp</p>
                <p class="text-xs text-gray-400 mt-0.5">+62 831-0826-7397</p>
            </div>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-700 ml-auto transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>

        <a href="mailto:admin@qurocollection.com"
            class="reveal delay-200 group flex items-center gap-5 border border-gray-100 rounded-2xl p-6 hover:border-gray-900 hover:shadow-sm transition-all duration-300">
            <div class="w-12 h-12 bg-blue-50 group-hover:bg-blue-100 rounded-xl flex items-center justify-center shrink-0 transition">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 group-hover:text-gray-700 transition">Email</p>
                <p class="text-xs text-gray-400 mt-0.5">admin@qurocollection.com</p>
            </div>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-700 ml-auto transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>

        <a href="https://www.instagram.com/quro.collection" target="_blank"
            class="reveal delay-300 group flex items-center gap-5 border border-gray-100 rounded-2xl p-6 hover:border-gray-900 hover:shadow-sm transition-all duration-300">
            <div class="w-12 h-12 bg-pink-50 group-hover:bg-pink-100 rounded-xl flex items-center justify-center shrink-0 transition">
                <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 group-hover:text-gray-700 transition">Instagram</p>
                <p class="text-xs text-gray-400 mt-0.5">@quro.collection</p>
            </div>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-700 ml-auto transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>

</section>

{{-- ── CTA ── --}}
<section class="bg-gray-950 py-20">
    <div class="max-w-6xl mx-auto px-6 text-center reveal">
        <p class="text-xs tracking-widest uppercase text-gray-600 mb-4">Mulai Sekarang</p>
        <h2 style="font-family: 'Playfair Display', serif;"
            class="text-3xl md:text-4xl font-semibold text-white mb-4 leading-tight">
            Temukan koleksi terbaik<br class="hidden md:block"> untuk tampil lebih elegan.
        </h2>
        <p class="text-gray-500 text-sm mb-10">Ratusan pilihan produk muslim premium menunggumu</p>
        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('shop.index') }}"
                class="bg-white text-gray-900 text-sm font-medium px-7 py-3 rounded-xl hover:bg-gray-100 transition">
                Lihat Koleksi
            </a>
            <a href="https://wa.me/6283108267397" target="_blank"
                class="border border-gray-700 text-gray-300 text-sm px-7 py-3 rounded-xl hover:border-white hover:text-white transition">
                Hubungi Kami
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
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => {
        observer.observe(el);
    });
</script>
@endpush

</x-app-layout>
