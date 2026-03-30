import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/pages/product-show.css',
                'resources/css/pages/checkout.css',
                'resources/css/pages/welcome.css',
                'resources/js/app.js',
                'resources/js/pages/otp.js',
                'resources/js/pages/cart.js',
                'resources/js/pages/checkout.js',
                'resources/js/pages/payment.js',
                'resources/js/pages/order-tracking.js',
                'resources/js/pages/welcome.js',
                'resources/js/pages/quick-view.js',
                'resources/js/pages/shop.js',
                'resources/js/pages/shop-category.js',
                'resources/js/pages/product-show.js',
            ],
            refresh: true,
        }),
    ],
});
