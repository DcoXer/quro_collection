<?php if (isset($component)) { $__componentOriginalaa758e6a82983efcbf593f765e026bd9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaa758e6a82983efcbf593f765e026bd9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => $__env->getContainer()->make(Illuminate\View\Factory::class)->make('mail::message'),'data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mail::message'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


# Pesanan Berhasil Dibuat! ✓

Halo **<?php echo new \Illuminate\Support\EncodedHtmlString($order->user->name); ?>**,

Terima kasih telah berbelanja di **Quro Collection**. Pesanan kamu telah kami terima dan kami akan proses setelah pembayaran selesai.

Thank You for shopping in **Quro Collection**. Your order has been received and will be processed once payment is completed.

---

## Detail Pesanan

| | |
|:--|:--|
| **Invoice** | <?php echo new \Illuminate\Support\EncodedHtmlString($order->invoice_number); ?> |
| **Tanggal** | <?php echo new \Illuminate\Support\EncodedHtmlString($order->created_at->format('d M Y, H:i')); ?> |
| **Status** | <?php echo new \Illuminate\Support\EncodedHtmlString(ucfirst($order->status)); ?> |
| **Metode Bayar** | <?php echo new \Illuminate\Support\EncodedHtmlString(ucfirst($order->payment_method ?? '-')); ?> |

---

## Item Pesanan

<?php if (isset($component)) { $__componentOriginal85530901ee91af5decf39e8ed3495cde = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85530901ee91af5decf39e8ed3495cde = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => $__env->getContainer()->make(Illuminate\View\Factory::class)->make('mail::table'),'data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mail::table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

| Produk | Size | Qty | Subtotal |
|:-------|:-----|:----|:---------|
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
| <?php echo new \Illuminate\Support\EncodedHtmlString($item->product->name ?? 'Produk dihapus'); ?> | <?php echo new \Illuminate\Support\EncodedHtmlString($item->size ?? '-'); ?> | <?php echo new \Illuminate\Support\EncodedHtmlString($item->quantity); ?> | Rp <?php echo new \Illuminate\Support\EncodedHtmlString(number_format($item->price * $item->quantity, 0, ',', '.')); ?> |
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
| | | **Total** | **Rp <?php echo new \Illuminate\Support\EncodedHtmlString(number_format($order->total_amount, 0, ',', '.')); ?>** |
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal85530901ee91af5decf39e8ed3495cde)): ?>
<?php $attributes = $__attributesOriginal85530901ee91af5decf39e8ed3495cde; ?>
<?php unset($__attributesOriginal85530901ee91af5decf39e8ed3495cde); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal85530901ee91af5decf39e8ed3495cde)): ?>
<?php $component = $__componentOriginal85530901ee91af5decf39e8ed3495cde; ?>
<?php unset($__componentOriginal85530901ee91af5decf39e8ed3495cde); ?>
<?php endif; ?>

---

## Alamat Pengiriman

**<?php echo new \Illuminate\Support\EncodedHtmlString($order->shipping_name); ?>**
<?php echo new \Illuminate\Support\EncodedHtmlString($order->shipping_phone); ?>

<?php echo new \Illuminate\Support\EncodedHtmlString($order->shipping_address); ?>


---

<?php if (isset($component)) { $__componentOriginal15a5e11357468b3880ae1300c3be6c4f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal15a5e11357468b3880ae1300c3be6c4f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => $__env->getContainer()->make(Illuminate\View\Factory::class)->make('mail::button'),'data' => ['url' => route('orders.show', $order->invoice_number),'color' => 'dark']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mail::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('orders.show', $order->invoice_number)),'color' => 'dark']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

Lihat Detail Pesanan
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal15a5e11357468b3880ae1300c3be6c4f)): ?>
<?php $attributes = $__attributesOriginal15a5e11357468b3880ae1300c3be6c4f; ?>
<?php unset($__attributesOriginal15a5e11357468b3880ae1300c3be6c4f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal15a5e11357468b3880ae1300c3be6c4f)): ?>
<?php $component = $__componentOriginal15a5e11357468b3880ae1300c3be6c4f; ?>
<?php unset($__componentOriginal15a5e11357468b3880ae1300c3be6c4f); ?>
<?php endif; ?>

Ada pertanyaan? Hubungi kami via WhatsApp di **+62 812 3456 7890**

© <?php echo new \Illuminate\Support\EncodedHtmlString(date('Y')); ?> Quro Collection · All rights reserved.

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaa758e6a82983efcbf593f765e026bd9)): ?>
<?php $attributes = $__attributesOriginalaa758e6a82983efcbf593f765e026bd9; ?>
<?php unset($__attributesOriginalaa758e6a82983efcbf593f765e026bd9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaa758e6a82983efcbf593f765e026bd9)): ?>
<?php $component = $__componentOriginalaa758e6a82983efcbf593f765e026bd9; ?>
<?php unset($__componentOriginalaa758e6a82983efcbf593f765e026bd9); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\qurocollection\resources\views/emails/order-confirmation.blade.php ENDPATH**/ ?>