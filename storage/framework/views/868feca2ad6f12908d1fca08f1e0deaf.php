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
<title>Pembayaran — Quro Collection</title>
<?php $__env->stopPush(); ?>

<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-lg">

        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gray-900 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-1">Langkah Terakhir</p>
            <h1 style="font-family: 'Playfair Display', serif;"
                class="text-3xl font-semibold text-gray-900">
                Selesaikan Pembayaran
            </h1>
        </div>

        
        <div class="bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm">

            
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs tracking-widest uppercase text-gray-400">Detail Pesanan</p>
                    <span class="text-xs bg-yellow-50 text-yellow-600 px-2.5 py-1 rounded-full font-medium">
                        Menunggu Pembayaran
                    </span>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Invoice</span>
                        <span class="font-medium text-gray-900"><?php echo e($order->invoice_number); ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Penerima</span>
                        <span class="font-medium text-gray-900"><?php echo e($order->shipping_name); ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Tanggal</span>
                        <span class="font-medium text-gray-900"><?php echo e($order->created_at->format('d M Y, H:i')); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <p class="text-xs tracking-widest uppercase text-gray-400 mb-3">Item Pesanan</p>
                <div class="space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">
                                <?php echo e($item->product->name ?? 'Produk'); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->size): ?> <span class="text-gray-400">(<?php echo e($item->size); ?>)</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                × <?php echo e($item->quantity); ?>

                            </span>
                            <span class="font-medium text-gray-900">
                                Rp <?php echo e(number_format($item->price * $item->quantity, 0, ',', '.')); ?>

                            </span>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>

            
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Total Pembayaran</span>
                    <span style="font-family: 'Playfair Display', serif;"
                        class="text-2xl font-semibold text-gray-900">
                        Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?>

                    </span>
                </div>
            </div>

            
            <div class="p-6">
                <button id="pay-button"
                    data-token="<?php echo e($order->payment_token); ?>"
                    data-success-url="<?php echo e(route('checkout.success', $order->invoice_number)); ?>"
                    data-pending-url="<?php echo e(route('orders.index')); ?>"
                    class="w-full bg-gray-900 text-white py-4 rounded-2xl text-sm font-medium hover:bg-gray-700 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Bayar Sekarang
                </button>

                <a href="<?php echo e(route('orders.index')); ?>"
                    class="block w-full text-center text-sm text-gray-400 hover:text-gray-600 transition mt-3">
                    Bayar Nanti
                </a>

                <p class="text-center text-xs text-gray-300 mt-4">
                    Pembayaran diproses secara aman oleh Midtrans
                </p>
            </div>

        </div>

        
        <div class="flex items-center justify-center gap-2 mt-6 text-xs text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            SSL Encrypted · Powered by Midtrans
        </div>

    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js'); ?>"
    data-client-key="<?php echo e($clientKey); ?>"></script>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/pages/payment.js']); ?>
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
<?php endif; ?><?php /**PATH C:\laragon\www\qurocollection\resources\views/checkout/payment.blade.php ENDPATH**/ ?>