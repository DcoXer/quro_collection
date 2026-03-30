<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusChart extends ChartWidget
{
    protected ?string $heading = 'Status Pesanan';
    protected static ?int $sort = 3;
    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $statuses = ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'];
        $labels   = ['Pending', 'Dibayar', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'];
        $colors   = ['#EAB308', '#3B82F6', '#8B5CF6', '#6366F1', '#22C55E', '#EF4444'];

        $data = array_map(fn ($s) => Order::where('status', $s)->count(), $statuses);

        return [
            'datasets' => [
                [
                    'data'            => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
