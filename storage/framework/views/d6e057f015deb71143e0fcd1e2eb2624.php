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

    <div class="max-w-2xl mx-auto px-4 py-8 md:py-12">

        <div class="mb-8">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-2">Akun Saya</p>
            <h1 style="font-family: 'Playfair Display', serif;"
                class="text-3xl font-semibold text-gray-900">Riwayat Pesanan</h1>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($orders->isEmpty()): ?>
            <div class="text-center py-20">
                <p class="text-gray-300 text-5xl mb-4">🛍</p>
                <p class="text-gray-500 mb-2">Belum ada pesanan</p>
                <a href="<?php echo e(route('shop.index')); ?>"
                    class="text-sm text-gray-900 underline underline-offset-2">Mulai belanja</a>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <a href="<?php echo e(route('orders.show', $order->invoice_number)); ?>"
                        class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl hover:border-gray-300 hover:shadow-sm transition bg-white">

                        <div>
                            <p class="text-sm font-semibold text-gray-900"><?php echo e($order->invoice_number); ?></p>
                            <p class="text-xs text-gray-400 mt-0.5"><?php echo e($order->created_at->format('d M Y, H:i')); ?></p>
                        </div>

                        <div class="text-right flex flex-col items-end gap-1.5">
                            <p class="text-sm font-semibold text-gray-900">
                                Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?>

                            </p>
                            <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'text-xs px-2.5 py-1 rounded-full font-medium',
                                'bg-yellow-50 text-yellow-600'  => $order->status === 'pending',
                                'bg-blue-50 text-blue-600'      => $order->status === 'paid',
                                'bg-purple-50 text-purple-600'  => $order->status === 'processing',
                                'bg-indigo-50 text-indigo-600'  => $order->status === 'shipped',
                                'bg-green-50 text-green-600'    => $order->status === 'delivered',
                                'bg-red-50 text-red-500'        => $order->status === 'cancelled',
                            ]); ?>"><?php echo e(ucfirst($order->status)); ?></span>
                        </div>

                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            <div class="mt-8">
                <?php echo e($orders->links()); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\qurocollection\resources\views/orders/index.blade.php ENDPATH**/ ?>