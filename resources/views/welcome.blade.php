<x-app-layout>

@push('seo')
<title>Quro Collection — Premium Muslim Fashion</title>
<meta name="description" content="Koleksi baju koko premium dengan desain modern dan bahan berkualitas tinggi untuk tampilan elegan sehari-hari.">
@endpush

@push('styles')
@vite(['resources/css/pages/welcome.css'])
@endpush

@php
    $fallbacks = [
        asset('images/produk.jpeg'),
        asset('images/produk1.JPEG'),
        asset('images/produk2.JPEG'),
        asset('images/produk3.JPEG'),
    ];
    $heroImgs = $heroSlides->map(fn($s) => \Illuminate\Support\Facades\Storage::url($s->image))
        ->pad(4, null)
        ->map(fn($url, $i) => $url ?? $fallbacks[$i])
        ->values();

    $starPath = 'M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z';
@endphp


{{-- ═══════════════════════════════════════════════════
     HERO
     Mobile  : fullscreen product image + floating text
     Desktop : existing editorial grid
════════════════════════════════════════════════════ --}}
<section id="hero-section" class="relative bg-white"
         style="min-height: calc(100svh - 80px);">

    {{-- ── Mobile background image ── --}}
    <div class="absolute inset-0 md:hidden"
         style="border-radius: 0 0 2rem 2rem; overflow: hidden;">
        <img src="{{ $heroImgs[0] }}"
             class="w-full h-full object-cover object-top parallax-img" alt="">
        <div class="absolute inset-0"
             style="background: linear-gradient(to top,
                rgba(0,0,0,0.88) 0%,
                rgba(0,0,0,0.40) 45%,
                rgba(0,0,0,0.18) 100%);"></div>
    </div>

    {{-- ── Mobile content ── --}}
    <div class="md:hidden relative z-10 flex flex-col px-5"
         style="min-height: calc(100svh - 80px);">

        {{-- Status bar --}}
        <div class="flex items-center justify-between pt-7 anim-fade-in">
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-[10px] tracking-widest uppercase text-white/50">
                    New Collection {{ now()->year }}
                </span>
            </div>
            <a href="{{ route('shop.index') }}"
               class="text-[10px] tracking-widest uppercase text-white/50 hover:text-white/80 transition">
                Koleksi
            </a>
        </div>

        {{-- Badge: customers ── top-left ── --}}
        <div class="absolute top-16 left-5 anim-fade-in delay-3">
            <div class="bg-white/10 backdrop-blur-xl border border-white/15 rounded-2xl px-3.5 py-2.5">
                <p class="text-white text-xs font-semibold leading-tight">
                    {{ number_format($totalCustomers) }}+ Pembeli
                </p>
                <div class="flex gap-px mt-1">
                    @for($s = 0; $s < 5; $s++)
                    <svg class="w-2.5 h-2.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="{{ $starPath }}"/>
                    </svg>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Badge: total sold ── top-right ── --}}
        <div class="absolute top-16 right-5 anim-fade-in delay-4">
            <div class="bg-white/10 backdrop-blur-xl border border-white/15 rounded-2xl px-3.5 py-2.5 text-right">
                <p class="text-white font-semibold leading-tight"
                   style="font-family:'Playfair Display',serif; font-size:1.25rem;">
                    {{ number_format($totalSold) }}+
                </p>
                <p class="text-white/45 text-[10px] mt-0.5">Produk Terjual</p>
            </div>
        </div>

        {{-- Spacer --}}
        <div class="flex-1 min-h-[60px]"></div>

        {{-- Bottom: headline + CTA --}}
        <div class="pb-8 anim-fade-up delay-1">
            <h1 class="font-semibold text-white leading-[1.05] mb-5"
                style="font-family:'Playfair Display',serif;
                       font-size: clamp(2.75rem, 14vw, 3.5rem);">
                {{ \App\Models\SiteSetting::get('hero_line1', 'Tampil') }}<br>
                <em class="italic" style="color:rgba(255,255,255,0.3);">{{ \App\Models\SiteSetting::get('hero_line2_italic', 'Elegan') }}</em><br>
                {{ \App\Models\SiteSetting::get('hero_line3', 'Setiap Hari') }}
            </h1>
            <p class="text-white/50 text-sm leading-relaxed mb-6" style="max-width:260px;">
                {{ \App\Models\SiteSetting::get('hero_subtext_mobile', 'Baju koko premium, desain modern, nyaman dipakai kapan saja.') }}
            </p>
            <div class="flex gap-3">
                <a href="{{ route('shop.index') }}"
                   class="bg-white text-gray-900 px-6 py-3 rounded-xl text-sm font-semibold
                          hover:bg-gray-100 active:scale-95 transition shrink-0">
                    Belanja Sekarang
                </a>
                <a href="{{ route('about') }}"
                   class="border border-white/20 text-white/70 px-5 py-3 rounded-xl text-sm
                          hover:border-white/40 hover:text-white transition shrink-0">
                    Tentang Kami
                </a>
            </div>
        </div>
    </div>

    {{-- ── Desktop: editorial grid ── --}}
    <div class="hidden md:block max-w-7xl mx-auto px-6 pt-10 pb-0">

        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-2 anim-fade-in">
                <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                <p class="text-xs tracking-widest uppercase text-gray-400">New Collection {{ now()->year }}</p>
            </div>
            <a href="{{ route('shop.index') }}"
               class="flex items-center gap-2 text-xs tracking-widest uppercase text-gray-500
                      hover:text-gray-900 transition anim-fade-in delay-1 group">
                Lihat Koleksi
                <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-12 gap-4 items-end">

            <div class="col-span-5 pb-16">
                <h1 style="font-family:'Playfair Display',serif;"
                    class="text-8xl font-semibold text-gray-900 leading-none mb-6 anim-fade-up">
                    {{ \App\Models\SiteSetting::get('hero_line1', 'Tampil') }}<br>
                    <em class="italic text-gray-300">{{ \App\Models\SiteSetting::get('hero_line2_italic', 'Elegan') }}</em><br>
                    {{ \App\Models\SiteSetting::get('hero_line3', 'Setiap Hari') }}
                </h1>
                <p class="text-sm text-gray-400 leading-relaxed max-w-xs mb-8 anim-fade-up delay-2">
                    {{ \App\Models\SiteSetting::get('hero_subtext_desktop', 'Koleksi baju koko premium dengan desain modern dan bahan berkualitas tinggi.') }}
                </p>
                <div class="flex gap-3 anim-fade-up delay-3">
                    <a href="{{ route('shop.index') }}"
                       class="bg-gray-900 text-white px-6 py-3 rounded-xl text-sm font-medium
                              hover:bg-gray-700 transition">
                        Belanja Sekarang
                    </a>
                    <a href="{{ route('about') }}"
                       class="border border-gray-200 text-gray-600 px-6 py-3 rounded-xl text-sm
                              hover:border-gray-900 hover:text-gray-900 transition">
                        Tentang Kami
                    </a>
                </div>
            </div>

            <div class="col-span-4 anim-scale-in delay-2 relative"
                 style="overflow:hidden; border-radius:1.5rem;">
                <div style="height:580px; overflow:hidden; border-radius:1.5rem;">
                    <img src="{{ $heroImgs[0] }}"
                         class="w-full h-full object-cover parallax-img"
                         style="height:130%; margin-top:-15%;" alt="">
                </div>
                <div class="absolute bottom-5 left-5 bg-white/90 backdrop-blur-sm rounded-2xl px-4 py-3 shadow-lg">
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-1.5">
                            @for($i = 0; $i < 3; $i++)
                            <div class="w-6 h-6 rounded-full bg-gray-200 border-2 border-white overflow-hidden">
                                <img src="{{ $heroImgs[$i] }}" class="w-full h-full object-cover" alt="">
                            </div>
                            @endfor
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-900">{{ number_format($totalCustomers) }}+ Pembeli</p>
                            <div class="flex gap-0.5 mt-0.5">
                                @for($s = 0; $s < 5; $s++)
                                <svg class="w-2.5 h-2.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="{{ $starPath }}"/>
                                </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-3 space-y-3 pb-4 anim-fade-up delay-4">
                <div class="img-hover rounded-2xl overflow-hidden" style="height:210px;">
                    <img src="{{ $heroImgs[1] }}" class="w-full h-full object-cover" alt="">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="img-hover rounded-2xl overflow-hidden" style="height:130px;">
                        <img src="{{ $heroImgs[2] }}" class="w-full h-full object-cover" alt="">
                    </div>
                    <div class="img-hover rounded-2xl overflow-hidden" style="height:130px;">
                        <img src="{{ $heroImgs[3] }}" class="w-full h-full object-cover" alt="">
                    </div>
                </div>
                <div class="bg-gray-950 rounded-2xl p-4">
                    <p style="font-family:'Playfair Display',serif;"
                       class="text-2xl font-semibold text-white">
                        {{ number_format($totalSold) }}+
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">Produk Terjual</p>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- MARQUEE --}}
<section class="py-3.5 overflow-hidden bg-gray-950">
    <div class="marquee-track flex gap-10 whitespace-nowrap">
        @foreach(array_fill(0, 10, null) as $_)
        <span class="text-[10px] tracking-widest uppercase text-gray-600 flex items-center gap-5">
            Quro Collection
            <span class="w-1 h-1 bg-gray-700 rounded-full inline-block"></span>
            Premium Muslim Fashion
            <span class="w-1 h-1 bg-gray-700 rounded-full inline-block"></span>
            Baju Koko Premium
            <span class="w-1 h-1 bg-gray-700 rounded-full inline-block"></span>
            New Collection {{ now()->year }}
            <span class="w-1 h-1 bg-gray-700 rounded-full inline-block"></span>
            Fast Delivery
            <span class="w-1 h-1 bg-gray-700 rounded-full inline-block"></span>
        </span>
        @endforeach
    </div>
</section>


{{-- FLASH SALE  (conditional) --}}
@if($activeFlashSale && $activeFlashSale->items->isNotEmpty())
@php $fsItems = $activeFlashSale->items->filter(fn($i) => $i->product && $i->product->is_active)->take(6); @endphp
@if($fsItems->isNotEmpty())
<section class="bg-gray-950 pt-10 pb-12 overflow-hidden" id="flash-sale">

    {{-- Header --}}
    <div class="px-4 md:px-6 max-w-6xl md:mx-auto mb-6 reveal">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 bg-amber-400 rounded-full block pulse-ring text-amber-400 shrink-0"></span>
                <div>
                    <p class="text-[10px] tracking-widest uppercase text-gray-500 mb-0.5">Penawaran Terbatas</p>
                    <h2 class="text-2xl font-semibold text-white"
                        style="font-family:'Playfair Display',serif;">
                        {{ $activeFlashSale->name }}
                    </h2>
                </div>
            </div>
            {{-- Countdown --}}
            <div id="flash-countdown" data-ends="{{ $activeFlashSale->ends_at->timestamp }}"
                 class="flex items-center gap-1.5 shrink-0">
                @foreach([['id'=>'cd-hours','label'=>'J'],['id'=>'cd-minutes','label'=>'M'],['id'=>'cd-seconds','label'=>'D']] as $cd)
                <div class="flex flex-col items-center">
                    <div class="bg-white/8 border border-white/10 rounded-lg w-11 h-11 flex items-center justify-center">
                        <span id="{{ $cd['id'] }}"
                              class="text-white text-base font-semibold countdown-digit font-mono">
                            00
                        </span>
                    </div>
                    <span class="text-[8px] tracking-widest text-gray-600 mt-0.5">{{ $cd['label'] }}</span>
                </div>
                @if(!$loop->last)
                <span class="text-gray-700 text-sm mb-2.5">:</span>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Horizontal strip --}}
    <div class="h-scroll pl-4 md:pl-6" style="padding-right: 1rem;">
        @foreach($fsItems as $i => $item)
        @php
            $p         = $item->product;
            $fp        = $item->flash_price;
            $discLabel = $item->discount_type === 'percent'
                ? '-'.$item->discount_value.'%'
                : 'Hemat Rp '.number_format($item->savings,0,',','.');
            $imgUrl    = $p->image
                ? \Illuminate\Support\Facades\Storage::url($p->image)
                : asset('images/produk.jpeg');
        @endphp
        <a href="{{ route('shop.show', $p) }}"
           class="h-scroll-card product-card relative rounded-2xl overflow-hidden block"
           style="width:68vw; max-width:240px; aspect-ratio:3/4;">
            <img src="{{ $imgUrl }}" alt="{{ $p->name }}"
                 class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0"
                 style="background:linear-gradient(to top,rgba(0,0,0,0.82) 0%,rgba(0,0,0,0.15) 55%,transparent 100%);">
            </div>
            {{-- Discount badge --}}
            <div class="absolute top-3 left-3">
                <span class="bg-amber-400 text-gray-950 text-[10px] font-bold px-2 py-1 rounded-lg leading-none">
                    {{ $discLabel }}
                </span>
            </div>
            {{-- Info --}}
            <div class="absolute bottom-0 left-0 right-0 p-4">
                <p class="text-white/60 text-[10px] mb-0.5">{{ $p->category?->name }}</p>
                <p class="text-white text-sm font-medium leading-tight mb-2 line-clamp-2">
                    {{ $p->name }}
                </p>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-amber-400 font-bold text-sm">
                        Rp {{ number_format($fp,0,',','.') }}
                    </span>
                    <span class="text-white/35 text-xs line-through">
                        Rp {{ number_format($p->price,0,',','.') }}
                    </span>
                </div>
            </div>
        </a>
        @endforeach

        {{-- End card --}}
        <a href="{{ route('shop.index') }}"
           class="h-scroll-card flex flex-col items-center justify-center gap-3 rounded-2xl
                  border border-white/10 hover:border-white/25 transition-colors"
           style="width:44vw; max-width:160px; aspect-ratio:3/4;">
            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </div>
            <p class="text-white/60 text-xs text-center px-3 leading-snug">Lihat Semua Produk</p>
        </a>
    </div>

</section>
@endif
@endif


{{-- ═══════════════════════════════════════════════════
     PRODUK TERBARU  — horizontal snap strip
════════════════════════════════════════════════════ --}}
@if($latestProducts->isNotEmpty())
<section class="bg-white pt-10 pb-12 overflow-hidden">

    {{-- Header --}}
    <div class="px-4 md:px-6 max-w-6xl md:mx-auto mb-5 reveal">
        <div class="flex items-end justify-between">
            <div>
                <p class="text-[10px] tracking-widest uppercase text-gray-400 mb-1.5">Koleksi Terkini</p>
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-900"
                    style="font-family:'Playfair Display',serif;">
                    Produk Terbaru
                </h2>
            </div>
            <div class="flex items-center gap-1.5 text-gray-400">
                <span class="text-xs hidden md:block">geser untuk lihat lebih</span>
                <span class="swipe-hint">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </span>
            </div>
        </div>
    </div>

    {{-- Strip --}}
    <div class="h-scroll pl-4 md:pl-6" style="padding-right:1rem;">
        @foreach($latestProducts as $i => $product)
        @php
            $imgUrl = $product->image
                ? \Illuminate\Support\Facades\Storage::url($product->image)
                : asset('images/produk.jpeg');
        @endphp
        <a href="{{ route('shop.show', $product) }}"
           class="h-scroll-card product-card relative rounded-2xl overflow-hidden block"
           style="width:72vw; max-width:260px; aspect-ratio:3/4;">
            <img src="{{ $imgUrl }}" alt="{{ $product->name }}"
                 class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0"
                 style="background:linear-gradient(to top,rgba(0,0,0,0.78) 0%,rgba(0,0,0,0.08) 50%,transparent 100%);">
            </div>
            {{-- Rating badge --}}
            @if(($product->review_count ?? 0) > 0)
            <div class="absolute top-3 right-3 bg-black/40 backdrop-blur-sm rounded-lg px-2 py-1
                        flex items-center gap-1">
                <svg class="w-2.5 h-2.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="{{ $starPath }}"/>
                </svg>
                <span class="text-white text-[10px] font-medium">
                    {{ number_format($product->avg_rating ?? 0, 1) }}
                </span>
            </div>
            @endif
            {{-- New badge --}}
            @if($i < 3)
            <div class="absolute top-3 left-3">
                <span class="bg-white text-gray-900 text-[9px] font-bold px-2 py-1 rounded-lg
                             tracking-wider uppercase leading-none">
                    Baru
                </span>
            </div>
            @endif
            {{-- Info --}}
            <div class="absolute bottom-0 left-0 right-0 p-4">
                <p class="text-white/55 text-[10px] mb-0.5">{{ $product->category?->name }}</p>
                <p class="text-white text-sm font-medium leading-tight mb-1.5 line-clamp-2">
                    {{ $product->name }}
                </p>
                <p class="text-white/80 text-sm font-semibold">
                    Rp {{ number_format($product->price,0,',','.') }}
                </p>
            </div>
        </a>
        @endforeach

        {{-- Lihat Semua card --}}
        <a href="{{ route('shop.index') }}"
           class="h-scroll-card flex flex-col items-center justify-center gap-3 rounded-2xl
                  border-2 border-dashed border-gray-200 hover:border-gray-400 transition-colors"
           style="width:44vw; max-width:160px; aspect-ratio:3/4;">
            <div class="w-11 h-11 rounded-full bg-gray-900 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </div>
            <div class="text-center px-3">
                <p class="text-sm font-medium text-gray-700 leading-tight">Lihat Semua</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $totalProducts }}+ produk</p>
            </div>
        </a>
    </div>

</section>
@endif


{{-- ═══════════════════════════════════════════════════
     STATS  — dark band, big counter numbers
════════════════════════════════════════════════════ --}}
<section class="bg-gray-950 py-10 md:py-14">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-0
                    divide-y divide-white/5 md:divide-y-0 md:divide-x md:divide-white/5">
            @foreach([
                ['raw' => $totalProducts,  'suffix' => '+', 'label' => 'Produk Tersedia',  'desc' => 'Koleksi lengkap'],
                ['raw' => $totalSold,      'suffix' => '+', 'label' => 'Produk Terjual',   'desc' => 'Kepercayaan bertumbuh'],
                ['raw' => $totalCustomers, 'suffix' => '+', 'label' => 'Pelanggan Puas',   'desc' => 'Seluruh Indonesia'],
                ['raw' => 100,             'suffix' => '%', 'label' => 'Garansi Kepuasan', 'desc' => 'Produk original'],
            ] as $i => $s)
            <div class="reveal stagger-{{ $i+1 }} py-6 px-5
                        {{ $i < 2 ? 'md:border-r-0' : '' }}">
                <p class="font-semibold text-white leading-none mb-2"
                   style="font-family:'Playfair Display',serif; font-size:clamp(2rem,6vw,3rem);"
                   data-counter
                   data-target="{{ $s['raw'] }}{{ $s['suffix'] }}">
                    {{ number_format($s['raw']) }}{{ $s['suffix'] }}
                </p>
                <p class="text-sm font-medium text-gray-400">{{ $s['label'] }}</p>
                <p class="text-xs text-gray-600 mt-0.5">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════
     KATEGORI  — editorial bento
     Mobile  : first = full-width tall, rest = horizontal strip
     Desktop : grid
════════════════════════════════════════════════════ --}}
@if($categories->isNotEmpty())
<section class="bg-gray-950 pt-10 pb-12 overflow-hidden">

    {{-- Header --}}
    <div class="px-4 md:px-6 max-w-6xl md:mx-auto mb-5 reveal">
        <div class="flex items-end justify-between">
            <div>
                <p class="text-[10px] tracking-widest uppercase text-gray-400 mb-1.5">Koleksi</p>
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-200"
                    style="font-family:'Playfair Display',serif;">
                    Kategori Pilihan
                </h2>
            </div>
            <a href="{{ route('shop.index') }}"
               class="hidden md:flex items-center gap-1.5 text-sm text-gray-400
                      hover:text-gray-900 transition group">
                Semua
                <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- MOBILE: first cat full-width, rest horizontal --}}
    @php
        $imgs  = [asset('images/produk.jpeg'),asset('images/produk1.JPEG'),
                  asset('images/produk2.JPEG'),asset('images/produk3.JPEG')];
        $catsArr = $categories->take(4);
        $firstCat = $catsArr->first();
        $restCats = $catsArr->skip(1);
    @endphp

    <div class="md:hidden">
        {{-- First: full-width editorial card --}}
        @if($firstCat)
        <div class="px-4 mb-3 reveal">
            <a href="{{ route('shop.category', $firstCat) }}"
               class="relative img-hover rounded-2xl overflow-hidden block" style="height:240px;">
                <img src="{{ $imgs[0] }}" alt="{{ $firstCat->name }}"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-5 flex items-end justify-between">
                    <div>
                        <p class="text-white font-semibold text-base">{{ $firstCat->name }}</p>
                        <p class="text-white/50 text-xs mt-0.5">{{ $firstCat->products_count }} produk</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-white/15 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
        @endif
        {{-- Rest: horizontal strip --}}
        @if($restCats->isNotEmpty())
        <div class="h-scroll pl-4" style="padding-right:1rem;">
            @foreach($restCats as $cat)
            <a href="{{ route('shop.category', $cat) }}"
               class="h-scroll-card relative img-hover rounded-2xl overflow-hidden block"
               style="width:55vw; max-width:200px; height:150px;">
                <img src="{{ $imgs[($loop->index + 1) % 4] }}" alt="{{ $cat->name }}"
                     class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-transparent to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-3">
                    <p class="text-white text-sm font-medium">{{ $cat->name }}</p>
                    <p class="text-white/50 text-[10px]">{{ $cat->products_count }} produk</p>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>

    {{-- DESKTOP: editorial grid --}}
    <div class="hidden md:block max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-{{ min($catsArr->count(), 4) }} gap-4">
            @foreach($catsArr as $i => $cat)
            <a href="{{ route('shop.category', $cat) }}"
               class="reveal stagger-{{ $i+1 }} group relative img-hover rounded-2xl overflow-hidden block"
               style="height:{{ $i === 0 ? '380px' : '300px' }};">
                <img src="{{ $imgs[$i % 4] }}" alt="{{ $cat->name }}"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-5">
                    <p class="text-white font-semibold text-base">{{ $cat->name }}</p>
                    <p class="text-white/60 text-xs mt-0.5">{{ $cat->products_count }} produk</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>

</section>
@endif


{{-- ═══════════════════════════════════════════════════
     STORY
════════════════════════════════════════════════════ --}}
<section class="bg-gray-50 border-t border-gray-100" id="story">
    <div class="max-w-6xl mx-auto px-6 py-16 md:py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16 items-center">

            <div class="reveal-left">
                <div class="grid grid-cols-12 grid-rows-2 gap-3" style="height:380px; md:height:480px;">
                    <div class="col-span-7 row-span-2 img-hover rounded-2xl overflow-hidden">
                        <img src="{{ $heroImgs[0] }}" class="w-full h-full object-cover" alt="">
                    </div>
                    <div class="col-span-5 img-hover rounded-2xl overflow-hidden">
                        <img src="{{ $heroImgs[1] }}" class="w-full h-full object-cover" alt="">
                    </div>
                    <div class="col-span-5 img-hover rounded-2xl overflow-hidden">
                        <img src="{{ $heroImgs[2] }}" class="w-full h-full object-cover" alt="">
                    </div>
                </div>
            </div>

            <div class="reveal-right">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-6 h-px bg-gray-400"></span>
                    <p class="text-[10px] tracking-widest uppercase text-gray-400">Cerita Kami</p>
                </div>
                <h2 class="text-2xl md:text-4xl font-semibold text-gray-900 mb-5 leading-snug"
                    style="font-family:'Playfair Display',serif;">
                    {{ \App\Models\SiteSetting::get('story_headline', 'Menghadirkan Elegansi dalam Setiap Jahitan') }}
                </h2>
                {{-- Mobile: icon bullets --}}
                <ul class="md:hidden space-y-3 mb-7">
                    @foreach([
                        ['icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'text'=> \App\Models\SiteSetting::get('story_bullet_1', 'Bahan premium, nyaman seharian')],
                        ['icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'text'=> \App\Models\SiteSetting::get('story_bullet_2', 'Proses & kirim dalam 1×24 jam')],
                        ['icon'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'text'=> \App\Models\SiteSetting::get('story_bullet_3', 'Garansi kepuasan setiap pembelian')],
                    ] as $b)
                    <li class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-gray-900 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $b['icon'] }}"/>
                            </svg>
                        </span>
                        <span class="text-sm text-gray-600">{{ $b['text'] }}</span>
                    </li>
                    @endforeach
                </ul>
                {{-- Desktop: full paragraphs --}}
                <div class="hidden md:block space-y-3 text-gray-500 text-sm leading-relaxed mb-7">
                    <p>{{ \App\Models\SiteSetting::get('story_body_desktop_1', 'Quro Collection lahir dari kecintaan terhadap fashion muslim yang modern. Kami percaya bahwa baju koko bukan sekadar pakaian ibadah, tapi juga pernyataan gaya yang bisa dikenakan kapan saja.') }}</p>
                    <p>{{ \App\Models\SiteSetting::get('story_body_desktop_2', 'Setiap produk kami dirancang dengan teliti, menggunakan bahan premium pilihan yang nyaman dipakai sepanjang hari.') }}</p>
                </div>
                <a href="{{ route('about') }}"
                   class="inline-flex items-center gap-2 text-sm font-medium text-gray-900
                          border-b border-gray-900 pb-0.5 hover:gap-3 transition-all">
                    Pelajari Lebih Lanjut
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════
     VALUES
════════════════════════════════════════════════════ --}}
<section class="bg-gray-950 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-6 py-16 md:py-20">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 reveal">
            <div>
                <p class="text-[10px] tracking-widest uppercase text-gray-400 mb-2">Nilai Kami</p>
                <h2 class="text-2xl md:text-4xl font-semibold text-gray-200"
                    style="font-family:'Playfair Display',serif;">
                    Mengapa Memilih<br>Quro Collection?
                </h2>
            </div>
            <a href="{{ route('shop.index') }}"
               class="mt-5 md:mt-0 inline-flex items-center gap-1.5 text-sm text-gray-400
                      hover:text-white transition group">
                Mulai Belanja
                <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
            $valueCards = [
                ['number'=>'01','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                 'title'=> \App\Models\SiteSetting::get('value_1_title', 'Kualitas Premium'),
                 'desc' => \App\Models\SiteSetting::get('value_1_desc', 'Bahan dipilih dengan ketat untuk kenyamanan dan ketahanan jangka panjang.'),
                 'delay'=>''],
                ['number'=>'02','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                 'title'=> \App\Models\SiteSetting::get('value_2_title', 'Pengiriman Cepat'),
                 'desc' => \App\Models\SiteSetting::get('value_2_desc', 'Pesanan diproses dalam 1×24 jam dan dikirim ke seluruh Indonesia.'),
                 'delay'=>'stagger-2'],
                ['number'=>'03','icon'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                 'title'=> \App\Models\SiteSetting::get('value_3_title', 'Garansi Kepuasan'),
                 'desc' => \App\Models\SiteSetting::get('value_3_desc', 'Kepuasan pelanggan adalah prioritas utama. Garansi untuk setiap pembelian.'),
                 'delay'=>'stagger-4'],
            ];
            @endphp
            @foreach($valueCards as $v)
            <div class="reveal {{ $v['delay'] }} group border border-white/10 rounded-2xl p-6
                        hover:border-white/30 hover:bg-white/5 transition-all duration-300">
                <div class="flex items-start justify-between mb-5">
                    <span class="text-5xl font-semibold text-white/20 group-hover:text-white/30 transition"
                          style="font-family:'Playfair Display',serif;">
                        {{ $v['number'] }}
                    </span>
                    <div class="rounded-xl flex items-center justify-center transition-colors duration-300 shrink-0 w-9 h-9"
                         style="background:rgba(255,255,255,0.08);">
                        <svg style="width:1.1rem;height:1.1rem;"
                             class="text-gray-300 group-hover:text-white transition-colors duration-300"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="1.5" d="{{ $v['icon'] }}"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-semibold text-white mb-1.5">{{ $v['title'] }}</h3>
                <p class="text-sm text-gray-400 leading-relaxed">{{ $v['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════
     TESTIMONIAL  — horizontal swipe strip
════════════════════════════════════════════════════ --}}
@if($testimonials->isNotEmpty())
<section class="bg-white border-t border-gray-100 pt-10 pb-12 overflow-hidden">

    {{-- Header --}}
    <div class="px-4 md:px-6 max-w-6xl md:mx-auto mb-5 reveal">
        <div class="flex items-end justify-between">
            <div>
                <p class="text-[10px] tracking-widest uppercase text-gray-400 mb-1.5">Ulasan Pelanggan</p>
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-900"
                    style="font-family:'Playfair Display',serif;">
                    Kata Mereka
                </h2>
            </div>
            <div class="flex items-center gap-px">
                @for($s = 0; $s < 5; $s++)
                <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="{{ $starPath }}"/>
                </svg>
                @endfor
            </div>
        </div>
    </div>

    {{-- Strip --}}
    <div class="h-scroll pl-4 md:pl-6" style="padding-right:1rem; align-items:stretch;">
        @foreach($testimonials as $i => $review)
        @php
            $parts    = explode(' ', trim($review->user->name ?? 'A'));
            $initials = strtoupper(substr($parts[0],0,1).(isset($parts[1])?substr($parts[1],0,1):''));
            $thumbUrl = $review->product?->image
                ? \Illuminate\Support\Facades\Storage::url($review->product->image)
                : null;
        @endphp
        <div class="h-scroll-card testimonial-card bg-white border border-gray-100 rounded-2xl p-5
                    flex flex-col justify-between"
             style="width:82vw; max-width:320px; min-height:180px;">
            {{-- Stars + product thumb --}}
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex gap-0.5">
                    @for($s = 1; $s <= 5; $s++)
                    <svg class="w-3 h-3 {{ $s <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}"
                         fill="currentColor" viewBox="0 0 20 20">
                        <path d="{{ $starPath }}"/>
                    </svg>
                    @endfor
                </div>
                @if($thumbUrl)
                <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-50 shrink-0">
                    <img src="{{ $thumbUrl }}" alt="" class="w-full h-full object-cover">
                </div>
                @endif
            </div>
            {{-- Quote --}}
            <p class="text-sm text-gray-700 leading-relaxed flex-1 mb-4 line-clamp-3">
                "{{ $review->comment }}"
            </p>
            {{-- User --}}
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-full bg-gray-900 flex items-center justify-center shrink-0">
                    <span class="text-white text-[10px] font-semibold">{{ $initials }}</span>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-900 truncate">
                        {{ $review->user->name ?? 'Pelanggan' }}
                    </p>
                    @if($review->product)
                    <p class="text-[10px] text-gray-400 truncate">{{ $review->product->name }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

</section>
@endif


{{-- ═══════════════════════════════════════════════════
     CTA BANNER
════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-gray-950">
    <img src="{{ $heroImgs[3] }}" alt=""
         class="absolute inset-0 w-full h-full object-cover opacity-20">
    <div class="absolute inset-0"
         style="background:linear-gradient(to right,rgba(3,7,18,1) 0%,rgba(3,7,18,0.8) 60%,rgba(3,7,18,0.45) 100%);">
    </div>
    <div class="relative z-10 max-w-6xl mx-auto px-6 py-20 md:py-28
                flex flex-col md:flex-row items-start md:items-center justify-between gap-10 reveal">
        <div>
            <p class="text-[10px] tracking-widest uppercase text-gray-500 mb-4">Mulai Sekarang</p>
            <h2 class="text-3xl md:text-5xl font-semibold text-white leading-snug mb-4"
                style="font-family:'Playfair Display',serif;">
                {{ \App\Models\SiteSetting::get('cta_headline', 'Temukan Koleksi Terbaik untuk Kamu') }}
            </h2>
            <p class="text-gray-400 text-sm max-w-sm leading-relaxed">
                {{ str_replace('{jumlah}', $totalProducts, \App\Models\SiteSetting::get('cta_subtext', 'Lebih dari {jumlah} produk siap menemani penampilan elegan kamu sehari-hari.')) }}
            </p>
        </div>
        <div class="flex flex-col gap-3 shrink-0 w-full md:w-auto">
            <a href="{{ route('shop.index') }}"
               class="inline-flex items-center justify-center gap-2 bg-white text-gray-900
                      px-7 py-3.5 rounded-xl text-sm font-semibold hover:bg-gray-100 transition">
                Belanja Sekarang
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <a href="{{ route('about') }}"
               class="inline-flex items-center justify-center gap-2 border border-gray-700
                      text-gray-300 px-7 py-3.5 rounded-xl text-sm
                      hover:border-gray-400 hover:text-white transition">
                Tentang Kami
            </a>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════
     STICKY CTA BAR (mobile only)
════════════════════════════════════════════════════ --}}
<div id="sticky-cta"
     class="fixed bottom-0 left-0 right-0 z-50 md:hidden translate-y-full"
     style="transition: transform 0.35s cubic-bezier(0.16,1,0.3,1);">
    <div class="bg-gray-900 border-t border-white/10 px-4 py-3 flex items-center justify-between gap-3"
         style="padding-bottom: max(0.75rem, env(safe-area-inset-bottom));">
        <p class="text-white text-sm font-medium">Quro Collection</p>
        <a href="{{ route('shop.index') }}"
           class="bg-white text-gray-900 px-5 py-2 rounded-xl text-sm font-semibold shrink-0 hover:bg-gray-100 transition">
            Belanja Sekarang →
        </a>
    </div>
</div>

@push('scripts')
@vite(['resources/js/pages/welcome.js'])
<script>
(function () {
    const bar    = document.getElementById('sticky-cta');
    const hero   = document.querySelector('section');           // first section = hero
    const footer = document.querySelector('footer') ?? document.querySelector('.site-footer');
    if (!bar) return;

    function update() {
        const heroBottom = hero ? hero.getBoundingClientRect().bottom : 300;
        const scrolled   = window.scrollY > 200 && heroBottom < 0;
        const nearFooter = footer
            ? footer.getBoundingClientRect().top < window.innerHeight + 80
            : false;

        if (scrolled && !nearFooter) {
            bar.style.transform = 'translateY(0)';
        } else {
            bar.style.transform = 'translateY(100%)';
        }
    }

    window.addEventListener('scroll', update, { passive: true });
    update();
})();
</script>
@endpush

</x-app-layout>
