<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Revenue 6 Bulan Terakhir';
    protected static ?int $sort = 2;
    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $months  = [];
        $revenue = [];

        for ($i = 5; $i >= 0; $i--) {
            $date      = now()->subMonths($i);
            $months[]  = $date->translatedFormat('M Y');
            $revenue[] = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount') / 1000; // dalam ribu rupiah
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Revenue (Rp ribu)',
                    'data'            => $revenue,
                    'borderColor'     => '#111827',
                    'backgroundColor' => 'rgba(17, 24, 39, 0.08)',
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
