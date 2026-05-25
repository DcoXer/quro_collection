<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusChart extends ChartWidget
{
    protected ?string $heading = 'Status Pesanan';
    protected static ?int $sort = 4;
    protected ?string $maxHeight = '180px';
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $statuses = ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'];
        $labels   = ['Pending', 'Dibayar', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'];
        $colors   = [
            'rgba(234, 179, 8,  0.75)',
            'rgba(59,  130, 246, 0.75)',
            'rgba(139, 92,  246, 0.75)',
            'rgba(99,  102, 241, 0.75)',
            'rgba(34,  197, 94,  0.75)',
            'rgba(239, 68,  68,  0.75)',
        ];

        $data = array_map(fn ($s) => Order::where('status', $s)->count(), $statuses);

        return [
            'datasets' => [
                [
                    'data'            => $data,
                    'backgroundColor' => $colors,
                    'borderWidth'     => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'polarArea';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels'   => [
                        'font'        => ['size' => 11],
                        'boxWidth'    => 10,
                        'padding'     => 12,
                    ],
                ],
            ],
            'scales' => [
                'r' => [
                    'grid'      => ['color' => 'rgba(100,116,139,0.1)'],
                    'ticks'     => ['display' => false],
                    'pointLabels' => ['display' => false],
                ],
            ],
        ];
    }
}
