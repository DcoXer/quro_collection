<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


<?php $__env->startPush('seo'); ?>
<title>Quro Collection — Premium Muslim Fashion</title>
<meta name="description" content="Koleksi baju koko premium dengan desain modern dan bahan berkualitas tinggi untuk tampilan elegan sehari-hari.">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/pages/welcome.css']); ?>
<?php $__env->stopPush(); ?>


<?php
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
?>

<section class="min-h-screen border-b border-gray-100 relative overflow-hidden bg-white">
    <div class="max-w-7xl mx-auto px-6 pt-10 pb-0">

        
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-2 anim-fade-in">
                <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                <p class="text-xs tracking-widest uppercase text-gray-400">New Collection <?php echo e(now()->year); ?></p>
            </div>
            <a href="<?php echo e(route('shop.index')); ?>"
                class="hidden md:flex items-center gap-2 text-xs tracking-widest uppercase text-gray-500 hover:text-gray-900 transition anim-fade-in delay-1 group">
                Lihat Koleksi
                <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        
        <div class="grid grid-cols-12 gap-4 items-end">

            
            <div class="col-span-12 md:col-span-5 pb-8 md:pb-16">
                <h1 style="font-family: 'Playfair Display', serif;"
                    class="text-6xl md:text-8xl font-semibold text-gray-900 leading-none mb-6 anim-fade-up">
                    Tampil<br>
                    <em class="italic text-gray-300">Elegan</em><br>
                    Setiap<br>Hari
                </h1>
                <p class="text-sm text-gray-400 leading-relaxed max-w-xs mb-8 anim-fade-up delay-2">
                    Koleksi baju koko premium dengan desain modern dan bahan berkualitas tinggi.
                </p>
                <div class="flex gap-3 anim-fade-up delay-3">
                    <a href="<?php echo e(route('shop.index')); ?>"
                        class="bg-gray-900 text-white px-6 py-3 rounded-xl text-sm font-medium hover:bg-gray-700 transition">
                        Belanja Sekarang
                    </a>
                    <a href="<?php echo e(route('about')); ?>"
                        class="border border-gray-200 text-gray-600 px-6 py-3 rounded-xl text-sm hover:border-gray-900 hover:text-gray-900 transition">
                        Tentang Kami
                    </a>
                </div>
            </div>

            
            <div class="col-span-12 md:col-span-4 anim-fade-in delay-2 relative">
                <div class="img-hover rounded-3xl overflow-hidden" style="height: 580px;">
                    <img src="<?php echo e($heroImgs[0]); ?>" class="w-full h-full object-cover" alt="">
                </div>
                
                <div class="absolute bottom-5 left-5 bg-white/90 backdrop-blur-sm rounded-2xl px-4 py-3 shadow-lg">
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-1.5">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 3; $i++): ?>
                                <div class="w-6 h-6 rounded-full bg-gray-200 border-2 border-white overflow-hidden">
                                    <img src="<?php echo e($heroImgs[$i]); ?>" class="w-full h-full object-cover" alt="">
                                </div>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-900"><?php echo e(number_format($totalCustomers)); ?>+ Pembeli</p>
                            <div class="flex gap-0.5 mt-0.5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($s = 0; $s < 5; $s++): ?>
                                    <svg class="w-2.5 h-2.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-span-12 md:col-span-3 space-y-3 pb-4 anim-fade-up delay-4">

                <div class="img-hover rounded-2xl overflow-hidden" style="height: 210px;">
                    <img src="<?php echo e($heroImgs[1]); ?>" class="w-full h-full object-cover" alt="">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="img-hover rounded-2xl overflow-hidden" style="height: 130px;">
                        <img src="<?php echo e($heroImgs[2]); ?>" class="w-full h-full object-cover" alt="">
                    </div>
                    <div class="img-hover rounded-2xl overflow-hidden" style="height: 130px;">
                        <img src="<?php echo e($heroImgs[3]); ?>" class="w-full h-full object-cover" alt="">
                    </div>
                </div>

                
                <div class="bg-gray-950 rounded-2xl p-4">
                    <p style="font-family: 'Playfair Display', serif;"
                        class="text-2xl font-semibold text-white"><?php echo e(number_format($totalSold)); ?>+</p>
                    <p class="text-xs text-gray-500 mt-0.5">Produk Terjual</p>
                </div>

            </div>
        </div>
    </div>
</section>


<section class="border-b border-gray-100 py-4 overflow-hidden bg-gray-950">
    <div class="marquee-track flex gap-10 whitespace-nowrap">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = array_fill(0, 10, null); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <span class="text-xs tracking-widest uppercase text-gray-500 flex items-center gap-6">
                Quro Collection
                <span class="w-1 h-1 bg-gray-700 rounded-full inline-block"></span>
                Premium Muslim Fashion
                <span class="w-1 h-1 bg-gray-700 rounded-full inline-block"></span>
                Baju Koko Premium
                <span class="w-1 h-1 bg-gray-700 rounded-full inline-block"></span>
                New Collection <?php echo e(now()->year); ?>

                <span class="w-1 h-1 bg-gray-700 rounded-full inline-block"></span>
                Fast Delivery
                <span class="w-1 h-1 bg-gray-700 rounded-full inline-block"></span>
            </span>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
</section>


<section class="border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-6 py-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                ['number' => $totalProducts . '+', 'label' => 'Produk Tersedia',  'desc' => 'Koleksi lengkap untuk semua momen', 'delay' => ''],
                ['number' => number_format($totalSold) . '+', 'label' => 'Produk Terjual', 'desc' => 'Kepercayaan yang terus bertumbuh', 'delay' => 'stagger-2'],
                ['number' => number_format($totalCustomers) . '+', 'label' => 'Pelanggan Puas', 'desc' => 'Dari seluruh Indonesia', 'delay' => 'stagger-3'],
                ['number' => (now()->year - 2025) . '+', 'label' => 'Tahun Berdiri', 'desc' => 'Pengalaman di industri fashion', 'delay' => 'stagger-4'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <div class="reveal <?php echo e($stat['delay']); ?>">
                    <p style="font-family: 'Playfair Display', serif;"
                        class="text-4xl md:text-5xl font-semibold text-gray-900 mb-1"><?php echo e($stat['number']); ?></p>
                    <p class="text-sm font-medium text-gray-700 mb-1"><?php echo e($stat['label']); ?></p>
                    <p class="text-xs text-gray-400"><?php echo e($stat['desc']); ?></p>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</section>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($categories->isNotEmpty()): ?>
<section class="border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-6 py-16">

        <div class="flex items-end justify-between mb-10 reveal">
            <div>
                <p class="text-xs tracking-widest uppercase text-gray-400 mb-3">Koleksi</p>
                <h2 style="font-family: 'Playfair Display', serif;"
                    class="text-3xl md:text-4xl font-semibold text-gray-900">Kategori Pilihan</h2>
            </div>
            <a href="<?php echo e(route('shop.index')); ?>"
                class="text-sm text-gray-400 hover:text-gray-900 transition hidden md:flex items-center gap-1.5 group">
                Semua Kategori
                <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-<?php echo e(min($categories->count(), 4)); ?> gap-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <?php $imgs = [asset('images/produk.jpeg'), asset('images/produk1.JPEG'), asset('images/produk2.JPEG'), asset('images/produk3.JPEG')]; ?>
            <a href="<?php echo e(route('shop.category', $cat)); ?>"
                class="reveal stagger-<?php echo e($i + 1); ?> group relative img-hover rounded-2xl overflow-hidden block"
                style="height: <?php echo e($i === 0 ? '380px' : '300px'); ?>;">
                <img src="<?php echo e($imgs[$i % 4]); ?>" alt="<?php echo e($cat->name); ?>"
                    class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-5">
                    <p class="text-white font-semibold text-base group-hover:translate-y-0 transition"><?php echo e($cat->name); ?></p>
                    <p class="text-white/60 text-xs mt-0.5"><?php echo e($cat->products_count); ?> produk</p>
                </div>
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<section class="border-b border-gray-100" id="story">
    <div class="max-w-6xl mx-auto px-6 py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">

            
            <div class="reveal-left">
                <div class="grid grid-cols-12 grid-rows-2 gap-3" style="height: 480px;">
                    <div class="col-span-7 row-span-2 img-hover rounded-3xl overflow-hidden">
                        <img src="<?php echo e($heroImgs[0]); ?>" class="w-full h-full object-cover" alt="">
                    </div>
                    <div class="col-span-5 img-hover rounded-3xl overflow-hidden">
                        <img src="<?php echo e($heroImgs[1]); ?>" class="w-full h-full object-cover" alt="">
                    </div>
                    <div class="col-span-5 img-hover rounded-3xl overflow-hidden">
                        <img src="<?php echo e($heroImgs[2]); ?>" class="w-full h-full object-cover" alt="">
                    </div>
                </div>
            </div>

            
            <div class="reveal-right">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-6 h-px bg-gray-400"></span>
                    <p class="text-xs tracking-widest uppercase text-gray-400">Cerita Kami</p>
                </div>
                <h2 style="font-family: 'Playfair Display', serif;"
                    class="text-3xl md:text-4xl font-semibold text-gray-900 mb-6 leading-snug">
                    Menghadirkan Elegansi<br>dalam Setiap Jahitan
                </h2>
                <div class="space-y-4 text-gray-500 text-sm leading-relaxed mb-8">
                    <p>Quro Collection lahir dari kecintaan terhadap fashion muslim yang modern. Kami percaya bahwa baju koko bukan sekadar pakaian ibadah, tapi juga pernyataan gaya yang bisa dikenakan kapan saja.</p>
                    <p>Setiap produk kami dirancang dengan teliti, menggunakan bahan premium pilihan yang nyaman dipakai sepanjang hari.</p>
                </div>
                <a href="<?php echo e(route('about')); ?>"
                    class="inline-flex items-center gap-2 text-sm font-medium text-gray-900 border-b border-gray-900 pb-0.5 hover:gap-3 transition-all">
                    Pelajari Lebih Lanjut
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

        </div>
    </div>
</section>


<section class="border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-6 py-20">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 reveal">
            <div>
                <p class="text-xs tracking-widest uppercase text-gray-400 mb-3">Nilai Kami</p>
                <h2 style="font-family: 'Playfair Display', serif;"
                    class="text-3xl md:text-4xl font-semibold text-gray-900">
                    Mengapa Memilih<br>Quro Collection?
                </h2>
            </div>
            <a href="<?php echo e(route('shop.index')); ?>"
                class="mt-6 md:mt-0 inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 transition group">
                Mulai Belanja
                <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                ['number' => '01', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                 'title' => 'Kualitas Premium', 'desc' => 'Bahan dipilih dengan ketat untuk memastikan kenyamanan dan ketahanan jangka panjang.', 'delay' => ''],
                ['number' => '02', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                 'title' => 'Pengiriman Cepat', 'desc' => 'Pesanan diproses dalam 1x24 jam dan dikirim ke seluruh Indonesia.', 'delay' => 'stagger-2'],
                ['number' => '03', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                 'title' => 'Garansi Kepuasan', 'desc' => 'Kepuasan pelanggan adalah prioritas utama kami. Garansi produk untuk setiap pembelian.', 'delay' => 'stagger-4'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <div class="reveal <?php echo e($value['delay']); ?> group border border-gray-100 rounded-2xl p-7 hover:border-gray-900 hover:shadow-sm transition-all duration-300">
                    <div class="flex items-start justify-between mb-6">
                        <span style="font-family: 'Playfair Display', serif;"
                            class="text-5xl font-semibold text-gray-100 group-hover:text-gray-200 transition">
                            <?php echo e($value['number']); ?>

                        </span>
                        <div class="w-10 h-10 bg-gray-50 group-hover:bg-gray-900 rounded-xl flex items-center justify-center transition-colors duration-300">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?php echo e($value['icon']); ?>"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-2"><?php echo e($value['title']); ?></h3>
                    <p class="text-sm text-gray-400 leading-relaxed"><?php echo e($value['desc']); ?></p>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</section>


<section class="relative overflow-hidden bg-gray-950">
    <img src="<?php echo e($heroImgs[3]); ?>" alt=""
        class="absolute inset-0 w-full h-full object-cover opacity-20">
    <div class="absolute inset-0 bg-gradient-to-r from-gray-950 via-gray-950/80 to-gray-950/50"></div>

    <div class="relative z-10 max-w-6xl mx-auto px-6 py-24 flex flex-col md:flex-row items-center justify-between gap-10 reveal">
        <div>
            <p class="text-xs tracking-widest uppercase text-gray-500 mb-4">Mulai Sekarang</p>
            <h2 style="font-family: 'Playfair Display', serif;"
                class="text-3xl md:text-5xl font-semibold text-white leading-snug mb-4">
                Temukan Koleksi<br>Terbaik untuk Kamu
            </h2>
            <p class="text-gray-400 text-sm max-w-sm">
                Lebih dari <?php echo e($totalProducts); ?> produk siap menemani penampilan elegan kamu sehari-hari.
            </p>
        </div>
        <div class="flex flex-col gap-3 shrink-0">
            <a href="<?php echo e(route('shop.index')); ?>"
                class="inline-flex items-center justify-center gap-2 bg-white text-gray-900 px-8 py-3.5 rounded-xl text-sm font-medium hover:bg-gray-100 transition">
                Belanja Sekarang
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <a href="<?php echo e(route('about')); ?>"
                class="inline-flex items-center justify-center gap-2 border border-gray-700 text-gray-300 px-8 py-3.5 rounded-xl text-sm hover:border-white hover:text-white transition">
                Tentang Kami
            </a>
        </div>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/pages/welcome.js']); ?>
<?php $__env->stopPush(); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\qurocollection\resources\views/welcome.blade.php ENDPATH**/ ?>