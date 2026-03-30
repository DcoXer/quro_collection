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

    <div class="max-w-xl mx-auto px-4 py-8 md:py-12">

        <a href="<?php echo e(route('orders.index')); ?>"
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 border border-gray-100 hover:border-gray-200 px-3 py-1.5 rounded-xl transition mb-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Pesanan Saya
        </a>

        
        <div class="mb-6">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-1">Invoice</p>
            <div class="flex items-center justify-between">
                <h1 style="font-family: 'Playfair Display', serif;"
                    class="text-xl font-semibold text-gray-900"><?php echo e($order->invoice_number); ?></h1>
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
            <p class="text-xs text-gray-400 mt-1"><?php echo e($order->created_at->format('d M Y, H:i')); ?></p>
        </div>

        
        <div class="border border-gray-100 rounded-2xl p-4 mb-4">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-3">Pengiriman</p>
            <p class="font-medium text-gray-900 text-sm"><?php echo e($order->shipping_name); ?></p>
            <p class="text-sm text-gray-500 mt-0.5"><?php echo e($order->shipping_phone); ?></p>
            <p class="text-sm text-gray-500 mt-0.5 leading-relaxed"><?php echo e($order->shipping_address); ?></p>
        </div>

        
        <div class="border border-gray-100 rounded-2xl p-4 mb-4">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-4">Status Pesanan</p>

            <?php
                $statuses = [
                    'pending'    => ['label' => 'Pesanan Diterima',   'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    'paid'       => ['label' => 'Pembayaran Lunas',   'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                    'processing' => ['label' => 'Sedang Diproses',    'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    'shipped'    => ['label' => 'Dalam Pengiriman',   'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                    'delivered'  => ['label' => 'Pesanan Diterima',   'icon' => 'M5 13l4 4L19 7'],
                    'cancelled'  => ['label' => 'Pesanan Dibatalkan', 'icon' => 'M6 18L18 6M6 6l12 12'],
                ];
                $order_flow = ['pending', 'paid', 'processing', 'shipped', 'delivered'];
                $current_index = array_search($order->status, $order_flow);
                $is_cancelled = $order->status === 'cancelled';
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($is_cancelled): ?>
                <div class="flex items-center gap-3 text-red-400">
                    <div class="w-9 h-9 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-red-500">Pesanan Dibatalkan</p>
                        <p class="text-xs text-gray-400"><?php echo e($order->updated_at->format('d M Y, H:i')); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="relative">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order_flow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php
                            $isDone    = $current_index !== false && $index <= $current_index;
                            $isCurrent = $current_index !== false && $index === $current_index;
                            $isLast    = $index === count($order_flow) - 1;
                        ?>

                        <div class="flex gap-4 <?php echo e(!$isLast ? 'mb-4' : ''); ?>">

                            
                            <div class="flex flex-col items-center">
                                <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'w-9 h-9 rounded-full flex items-center justify-center text-sm shrink-0 transition',
                                    'bg-gray-900 text-white'  => $isDone,
                                    'bg-gray-100 text-gray-300' => !$isDone,
                                ]); ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($statuses[$status]['icon']); ?>"/>
                                    </svg>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isLast): ?>
                                    <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                        'w-0.5 h-8 mt-1',
                                        'bg-gray-900' => $isDone && $current_index > $index,
                                        'bg-gray-100' => !($isDone && $current_index > $index),
                                    ]); ?>"></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            
                            <div class="pt-1.5">
                                <p class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'text-sm font-medium',
                                    'text-gray-900' => $isDone,
                                    'text-gray-300' => !$isDone,
                                ]); ?>">
                                    <?php echo e($statuses[$status]['label']); ?>

                                </p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isCurrent): ?>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        <?php echo e($order->updated_at->format('d M Y, H:i')); ?>

                                    </p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->tracking_number && $order->courier): ?>
        <div id="tracking-section"
            data-url="<?php echo e(route('orders.track', $order->invoice_number)); ?>"
            class="border border-gray-100 rounded-2xl p-4 mb-4">
            <div class="flex items-center justify-between mb-4">
                <p class="text-xs tracking-widest uppercase text-gray-400">Lacak Paket</p>
                <div class="flex items-center gap-2">
                    <span class="text-xs bg-gray-50 text-gray-600 px-2 py-1 rounded-lg font-medium uppercase">
                        <?php echo e($order->courier); ?>

                    </span>
                    <span class="text-xs text-gray-500 font-mono"><?php echo e($order->tracking_number); ?></span>
                </div>
            </div>

            
            <div id="tracking-loading" class="flex items-center justify-center py-8">
                <div class="w-6 h-6 border-2 border-gray-900 border-t-transparent rounded-full animate-spin"></div>
            </div>

            
            <div id="tracking-result" class="hidden"></div>

            
            <div id="tracking-error" class="hidden text-center py-6">
                <p class="text-sm text-gray-400">Gagal memuat data tracking.</p>
                <button onclick="loadTracking()" class="text-xs text-gray-900 underline mt-2">Coba lagi</button>
            </div>
        </div>
        <?php elseif(in_array($order->status, ['processing', 'shipped', 'delivered'])): ?>
        <div class="border border-gray-100 rounded-2xl p-4 mb-4">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-2">Lacak Paket</p>
            <p class="text-sm text-gray-400">Nomor resi belum tersedia. Silakan hubungi kami via WhatsApp.</p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="border border-gray-100 rounded-2xl p-4 mb-4">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-3">Item Pesanan</p>
            <div class="space-y-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                <?php echo e($item->product->name ?? 'Produk dihapus'); ?>

                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                <?php echo e($item->size); ?> x<?php echo e($item->quantity); ?>

                            </p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">
                            Rp <?php echo e(number_format($item->price * $item->quantity, 0, ',', '.')); ?>

                        </p>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'delivered'): ?>
        <div class="border border-gray-100 rounded-2xl p-4 mb-4">
            <p class="text-xs tracking-widest uppercase text-gray-400 mb-4">Ulasan Produk</p>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="mb-4 text-xs text-green-600 bg-green-50 border border-green-100 rounded-xl px-3 py-2">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="space-y-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->product): ?>
                        <?php $existing = $existingReviews[$item->product_id] ?? null; ?>
                        <div class="pb-6 border-b border-gray-50 last:border-0 last:pb-0">

                            
                            <div class="flex items-center gap-3 mb-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->product->image): ?>
                                    <img src="<?php echo e(Storage::url($item->product->image)); ?>"
                                        alt="<?php echo e($item->product->name); ?>"
                                        class="w-10 h-10 rounded-xl object-cover shrink-0">
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 shrink-0"></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div>
                                    <p class="text-sm font-medium text-gray-900"><?php echo e($item->product->name); ?></p>
                                    <p class="text-xs text-gray-400"><?php echo e($item->size); ?></p>
                                </div>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existing): ?>
                                
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <div class="flex items-center gap-1 mb-1">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                            <svg class="w-4 h-4 <?php echo e($i <= $existing->rating ? 'text-yellow-400' : 'text-gray-200'); ?>"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <span class="text-xs text-gray-400 ml-1"><?php echo e($existing->created_at->format('d M Y')); ?></span>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existing->comment): ?>
                                        <p class="text-sm text-gray-600"><?php echo e($existing->comment); ?></p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <form method="POST" action="<?php echo e(route('reviews.destroy', $existing->id)); ?>" class="mt-2">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                            class="text-xs text-red-400 hover:text-red-600 transition">
                                            Hapus ulasan
                                        </button>
                                    </form>
                                </div>
                            <?php else: ?>
                                
                                <form method="POST" action="<?php echo e(route('reviews.store')); ?>"
                                    x-data="{ rating: 0, hover: 0 }">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="product_id" value="<?php echo e($item->product_id); ?>">
                                    <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
                                    <input type="hidden" name="rating" x-model="rating">

                                    
                                    <div class="flex items-center gap-1 mb-3">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                            <button type="button"
                                                @mouseenter="hover = <?php echo e($i); ?>"
                                                @mouseleave="hover = 0"
                                                @click="rating = <?php echo e($i); ?>"
                                                class="focus:outline-none">
                                                <svg class="w-7 h-7 transition-colors"
                                                    :class="(hover || rating) >= <?php echo e($i); ?> ? 'text-yellow-400' : 'text-gray-200'"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <span class="text-xs text-gray-400 ml-1" x-show="rating > 0">
                                            <span x-text="['', 'Sangat Buruk', 'Buruk', 'Cukup', 'Bagus', 'Sangat Bagus'][rating]"></span>
                                        </span>
                                    </div>

                                    
                                    <textarea name="comment" rows="2"
                                        placeholder="Tulis ulasanmu (opsional)..."
                                        class="w-full text-sm border border-gray-100 rounded-xl px-3 py-2 focus:outline-none focus:border-gray-300 resize-none placeholder-gray-300 transition"></textarea>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-xs text-red-400 mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <button type="submit"
                                        x-bind:disabled="rating === 0"
                                        class="mt-2 text-xs bg-gray-900 text-white px-4 py-2 rounded-xl disabled:opacity-30 disabled:cursor-not-allowed hover:bg-gray-700 transition">
                                        Kirim Ulasan
                                    </button>
                                </form>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="flex justify-between items-center px-1">
            <span class="text-sm text-gray-500">Total Pembayaran</span>
            <span style="font-family: 'Playfair Display', serif;"
                class="text-xl font-semibold text-gray-900">
                Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?>

            </span>
        </div>

        <div class="mt-8">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'pending' && $order->payment_token): ?>
                <a href="<?php echo e(route('checkout.payment', $order->invoice_number)); ?>"
                    class="block text-center bg-gray-900 text-white py-3 rounded-xl text-sm font-medium hover:bg-gray-700 transition mb-3">
                    Selesaikan Pembayaran
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <a href="<?php echo e(route('shop.index')); ?>"
                class="block text-center border border-gray-200 text-gray-600 py-3 rounded-xl text-sm hover:border-gray-900 transition">
                Lanjut Belanja
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'pending'): ?>
                <form id="delete-order-form" method="POST"
                    action="<?php echo e(route('orders.destroy', $order->invoice_number)); ?>"
                    class="mt-3">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="button"
                        onclick="showConfirm(
                            'Batalkan Pesanan',
                            'Pesanan <?php echo e($order->invoice_number); ?> akan dibatalkan dan stok akan dikembalikan. Lanjutkan?',
                            () => document.getElementById('delete-order-form').submit()
                        )"
                        class="w-full text-center border border-red-100 text-red-400 py-3 rounded-xl text-sm hover:bg-red-50 hover:border-red-300 transition">
                        Batalkan Pesanan
                    </button>
                </form>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <?php $__env->startPush('scripts'); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->tracking_number && $order->courier): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/js/pages/order-tracking.js']); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php endif; ?><?php /**PATH C:\laragon\www\qurocollection\resources\views/orders/show.blade.php ENDPATH**/ ?>