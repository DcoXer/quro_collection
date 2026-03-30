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
    <title>Checkout - Quro Collection</title>
    <?php $__env->stopPush(); ?>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/pages/checkout.css']); ?>

    <div class="max-w-6xl mx-auto px-4 py-8 md:py-12">

        
        <div class="mb-8">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-1.5">Pembelian</p>
            <h1 style="font-family: 'Playfair Display', serif;" class="text-3xl font-semibold text-gray-900">Checkout</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            
            <div class="lg:col-span-2 space-y-4">
                <form id="checkout-form" method="POST" action="<?php echo e(route('checkout.store')); ?>">
                    <?php echo csrf_field(); ?>

                    
                    <div class="checkout-card">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="checkout-step-icon"><span>1</span></div>
                            <h2 class="checkout-card-title">Informasi Penerima</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="checkout-label">Nama Penerima</label>
                                <input type="text" name="shipping_name"
                                    value="<?php echo e(old('shipping_name', auth()->user()->name)); ?>"
                                    class="checkout-input">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['shipping_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1.5"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="checkout-label">Nomor HP</label>
                                <input type="text" name="shipping_phone"
                                    value="<?php echo e(old('shipping_phone', auth()->user()->phone)); ?>"
                                    placeholder="08xxxxxxxxxx"
                                    class="checkout-input">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['shipping_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1.5"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
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
                                    class="checkout-input" style="resize: none;"><?php echo e(old('shipping_address', auth()->user()->address_detail)); ?></textarea>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['shipping_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1.5"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="checkout-card">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="checkout-step-icon"><span>3</span></div>
                            <h2 class="checkout-card-title">Estimasi Ongkos Pengiriman</h2>
                        </div>

                        <input type="hidden" name="courier" id="courier-hidden" value="all">
                        <input type="hidden" name="courier_service" id="courier-service">
                        <input type="hidden" name="shipping_cost" id="shipping-cost" value="0">

                        <div id="ongkir-result" class="hidden">
                            <p class="checkout-label mb-3">Layanan Pengiriman</p>
                            <div id="ongkir-list" class="space-y-2"></div>
                        </div>

                        <p id="ongkir-placeholder" class="text-sm text-gray-400">
                            Lengkapi alamat hingga kelurahan untuk melihat estimasi ongkir.
                        </p>
                    </div>

                    
                    <div class="checkout-card">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="checkout-step-icon"><span>4</span></div>
                            <h2 class="checkout-card-title">
                                Kode Voucher
                                <span class="text-gray-400 font-normal text-xs ml-1">(opsional)</span>
                            </h2>
                        </div>

                        <div id="voucher-applied" class="<?php echo e(session('voucher') ? '' : 'hidden'); ?>">
                            <div class="voucher-applied">
                                <div>
                                    <p class="text-sm font-semibold text-green-700"><?php echo e(session('voucher.code')); ?></p>
                                    <p class="text-xs text-green-500 mt-0.5">
                                        Hemat Rp <?php echo e(number_format(session('voucher.discount', 0), 0, ',', '.')); ?>

                                    </p>
                                </div>
                                <button type="button" onclick="removeVoucher()"
                                    class="text-green-400 hover:text-red-400 transition text-lg leading-none">✕</button>
                            </div>
                        </div>

                        <div id="voucher-form" class="<?php echo e(session('voucher') ? 'hidden' : ''); ?>">
                            <div class="flex gap-2">
                                <input type="text" id="voucher-input"
                                    placeholder="Masukkan kode voucher"
                                    class="checkout-input uppercase" style="flex: 1;">
                                <button type="button" onclick="applyVoucher()"
                                    class="bg-gray-900 text-white px-5 rounded-xl text-sm font-medium hover:bg-gray-700 transition whitespace-nowrap">
                                    Pakai
                                </button>
                            </div>
                        </div>
                    </div>

                    
                    <div class="lg:hidden space-y-3">
                        <button type="submit" class="btn-primary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Buat Pesanan
                        </button>
                        <a href="<?php echo e(route('cart.index')); ?>" class="btn-secondary">Batalkan</a>
                    </div>

                </form>
            </div>

            
            <div class="lg:col-span-1">
                <div class="summary-card">

                    <p style="font-family: 'Playfair Display', serif;"
                        class="text-lg font-semibold text-gray-900 mb-5">Ringkasan</p>

                    
                    <div class="space-y-3 mb-5">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="flex gap-3 items-center">
                            <div class="summary-item-thumb">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['image']): ?>
                                    <img src="<?php echo e(Storage::url($item['image'])); ?>" alt="<?php echo e($item['name']); ?>">
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-900 truncate"><?php echo e($item['name']); ?></p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    <?php echo e($item['size'] ?? '-'); ?> &times; <?php echo e($item['quantity']); ?>

                                </p>
                            </div>
                            <p class="text-xs font-semibold text-gray-900 shrink-0">
                                Rp <?php echo e(number_format($item['price'] * $item['quantity'], 0, ',', '.')); ?>

                            </p>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>

                    <div class="checkout-divider"></div>

                    
                    <div class="space-y-2.5 mb-4">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Subtotal</span>
                            <span>Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                        </div>
                        <div id="ongkir-row" class="hidden flex justify-between text-sm text-gray-500">
                            <span id="ongkir-label">Ongkos Kirim</span>
                            <span id="ongkir-amount">Rp 0</span>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('voucher')): ?>
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Diskon</span>
                            <span>- Rp <?php echo e(number_format(session('voucher.discount', 0), 0, ',', '.')); ?></span>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="checkout-divider"></div>

                    
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-sm font-medium text-gray-700">Total</span>
                        <span style="font-family: 'Playfair Display', serif;"
                            id="final-total"
                            class="text-2xl font-semibold text-gray-900">
                            Rp <?php echo e(number_format($total - session('voucher.discount', 0), 0, ',', '.')); ?>

                        </span>
                    </div>

                    
                    <div class="hidden lg:block space-y-3">
                        <button type="submit" form="checkout-form" class="btn-primary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Buat Pesanan
                        </button>
                        <a href="<?php echo e(route('cart.index')); ?>" class="btn-secondary">Batalkan</a>
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

    <?php $__env->startPush('scripts'); ?>
    <script>
        window.CheckoutConfig = {
            baseTotal: <?php echo e($total - session('voucher.discount', 0)); ?>,
            weight: <?php echo e($weight ?? 0.5); ?>,
            urls: {
                provinsi:      '<?php echo e(route('wilayah.provinsi')); ?>',
                kabupaten:     '<?php echo e(url('wilayah/kabupaten')); ?>',
                kecamatan:     '<?php echo e(url('wilayah/kecamatan')); ?>',
                kelurahan:     '<?php echo e(url('wilayah/kelurahan')); ?>',
                ongkir:        '<?php echo e(route('ongkir.check')); ?>',
                voucherApply:  '<?php echo e(route('voucher.apply')); ?>',
                voucherRemove: '<?php echo e(route('voucher.remove')); ?>',
            },
        };
    </script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/pages/checkout.js']); ?>
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
<?php endif; ?><?php /**PATH C:\laragon\www\qurocollection\resources\views/checkout/index.blade.php ENDPATH**/ ?>