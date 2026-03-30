<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $paidStatuses = ['paid', 'processing', 'shipped', 'delivered'];

        $totalRevenue      = Order::whereIn('status', $paidStatuses)->sum('total_amount');
        $monthRevenue      = Order::whereIn('status', $paidStatuses)
                                ->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->sum('total_amount');
        $lastMonthRevenue  = Order::whereIn('status', $paidStatuses)
                                ->whereMonth('created_at', now()->subMonth()->month)
                                ->whereYear('created_at', now()->subMonth()->year)
                                ->sum('total_amount');

        $revenueTrend = $lastMonthRevenue > 0
            ? round((($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        $totalOrders   = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $todayOrders   = Order::whereDate('created_at', today())->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();

        $totalCustomers   = User::count();
        $newThisMonth     = User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

        $totalProducts    = Product::where('is_active', true)->count();
        $lowStock         = Product::where('is_active', true)->where('stock', '<=', 5)->where('stock', '>', 0)->count();
        $outOfStock       = Product::where('is_active', true)->where('stock', 0)->count();

        $totalReviews     = ProductReview::count();
        $avgRating        = round(ProductReview::avg('rating') ?? 0, 1);

        // Sparkline: orders last 7 days
        $last7 = collect(range(6, 0))->map(fn ($d) =>
            Order::whereDate('created_at', now()->subDays($d))->count()
        )->toArray();

        return [
            Stat::make('Revenue Bulan Ini', 'Rp ' . number_format($monthRevenue, 0, ',', '.'))
                ->description(($revenueTrend >= 0 ? '+' : '') . $revenueTrend . '% vs bulan lalu · sudah bayar')
                ->descriptionIcon($revenueTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueTrend >= 0 ? 'success' : 'danger')
                ->chart($last7),

            Stat::make('Total Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Hanya pesanan yang sudah bayar')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pesanan Hari Ini', $todayOrders)
                ->description($pendingOrders . ' pending · ' . $totalOrders . ' total')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Pesanan Selesai', $deliveredOrders)
                ->description('Terkirim ke pelanggan')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Total Customer', $totalCustomers)
                ->description($newThisMonth . ' baru bulan ini')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Stok Produk', $totalProducts . ' aktif')
                ->description($outOfStock . ' habis · ' . $lowStock . ' menipis')
                ->descriptionIcon('heroicon-m-tag')
                ->color($outOfStock > 0 ? 'danger' : ($lowStock > 0 ? 'warning' : 'gray')),

            Stat::make('Rating Rata-rata', $avgRating . ' / 5')
                ->description($totalReviews . ' ulasan masuk')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}
