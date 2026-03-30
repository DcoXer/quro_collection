<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('images/logo/apple-touch-icon.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('images/logo/favicon-32x32.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('images/logo/favicon-16x16.png')); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('images/logo/favicon.ico')); ?>">
    <link rel="manifest" href="<?php echo e(asset('images/logo/site.webmanifest')); ?>">
    <title>Masuk — Quro Collection</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-white antialiased" style="font-family: 'Inter', sans-serif;">

<div class="min-h-screen flex">

    
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden"
        style="background: url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1200') center/cover no-repeat;">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);"></div>
        <div class="relative z-10 flex flex-col justify-between p-12 w-full">

            
            <a href="<?php echo e(route('shop.index')); ?>" class="flex items-center gap-3">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Quro Collection" class="h-10 w-auto">
                <span style="font-family: 'Playfair Display', serif;"
                    class="text-white text-xl font-semibold tracking-wide">
                    Quro Collection
                </span>
            </a>

            
            <div>
                <p style="font-family: 'Playfair Display', serif;"
                    class="text-white text-4xl font-semibold leading-snug mb-4">
                    Elegansi Muslim Fashion<br>untuk Setiap Momen.
                </p>
                <p class="text-white/60 text-sm">
                    Koleksi baju koko premium dengan desain modern dan bahan berkualitas tinggi.
                </p>
            </div>

        </div>
    </div>

    
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 bg-gray-50">
        <div class="w-full max-w-md">

            
            <div class="flex justify-center mb-8 lg:hidden">
                <a href="<?php echo e(route('shop.index')); ?>" class="flex items-center gap-2">
                    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Quro Collection" class="h-10 w-auto">
                </a>
            </div>

            <div class="mb-8">
                <h1 style="font-family: 'Playfair Display', serif;"
                    class="text-3xl font-semibold text-gray-900 mb-1">Selamat Datang</h1>
                <p class="text-sm text-gray-400">Masuk ke akun Quro Collection kamu</p>
            </div>

            <?php if (isset($component)) { $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-session-status','data' => ['class' => 'mb-4','status' => session('status')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-session-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4','status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('status'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $attributes = $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $component = $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                        placeholder="email@example.com"
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gray-400 transition">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password" required
                        placeholder="••••••••"
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gray-400 transition">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-500 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300">
                        Ingat saya
                    </label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('password.request')): ?>
                        <a href="<?php echo e(route('password.request')); ?>"
                            class="text-gray-400 hover:text-gray-700 transition">
                            Lupa password?
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <button type="submit"
                    class="w-full bg-gray-900 text-white py-3.5 rounded-xl text-sm font-medium hover:bg-gray-700 transition">
                    Masuk
                </button>

                <div class="relative my-2">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-100"></div>
                    </div>
                </div>

                <a href="<?php echo e(route('register')); ?>"
                    class="block w-full text-center border border-gray-200 text-gray-700 py-3.5 rounded-xl text-sm font-medium hover:border-gray-400 transition">
                    Buat Akun Baru
                </a>

                <div class="relative my-2">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-100"></div>
                    </div>
                    <div class="relative flex justify-center text-xs text-gray-400 bg-gray-50 px-3">
                        atau masuk dengan
                    </div>
                </div>
                <a href="<?php echo e(route('auth.google')); ?>"
                    class="flex items-center justify-center gap-3 w-full border border-gray-200 py-3 rounded-xl text-sm text-gray-700 hover:border-gray-400 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Masuk dengan Google
                </a>
            </form>

            <p class="text-center text-xs text-gray-300 mt-8">
                © <?php echo e(date('Y')); ?> Quro Collection. All rights reserved.
            </p>
        </div>
    </div>

</div>

</body>
</html><?php /**PATH C:\laragon\www\qurocollection\resources\views/auth/login.blade.php ENDPATH**/ ?>