<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $paidStatuses = ['paid', 'processing', 'shipped', 'delivered'];

        $totalOrders   = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();

        $monthRevenue     = Order::whereIn('status', $paidStatuses)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
        $lastMonthRevenue = Order::whereIn('status', $paidStatuses)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');

        $revenueTrend = $lastMonthRevenue > 0
            ? round((($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        $revenueFormatted = $monthRevenue >= 1000000
            ? 'Rp ' . number_format($monthRevenue / 1000000, 1, ',', '.') . 'jt'
            : 'Rp ' . number_format($monthRevenue / 1000, 0, ',', '.') . 'rb';

        $totalProducts = Product::where('is_active', true)->count();
        $outOfStock    = Product::where('is_active', true)->where('stock', 0)->count();
        $lowStock      = Product::where('is_active', true)->where('stock', '<=', 5)->where('stock', '>', 0)->count();

        // Sparkline: orders last 7 days
        $last7 = collect(range(6, 0))->map(
            fn ($d) => Order::whereDate('created_at', now()->subDays($d))->count()
        )->toArray();

        return [
            Stat::make('Total Pesanan', $totalOrders)
                ->description($pendingOrders . ' pending menunggu konfirmasi')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary')
                ->chart($last7),

            Stat::make('Revenue Bulan Ini', $revenueFormatted)
                ->description(($revenueTrend >= 0 ? '+' : '') . $revenueTrend . '% vs bulan lalu')
                ->descriptionIcon($revenueTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueTrend >= 0 ? 'success' : 'danger'),

            Stat::make('Total Produk', $totalProducts . ' aktif')
                ->description($outOfStock . ' habis · ' . $lowStock . ' menipis')
                ->descriptionIcon('heroicon-m-cube')
                ->color($outOfStock > 0 ? 'danger' : ($lowStock > 0 ? 'warning' : 'gray')),
        ];
    }
}
