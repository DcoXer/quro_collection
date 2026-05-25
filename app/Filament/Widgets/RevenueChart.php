<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Revenue Bulanan';
    protected static ?int $sort = 3;
    protected ?string $maxHeight = '180px';

    protected function getData(): array
    {
        $months  = [];
        $revenue = [];

        for ($i = 11; $i >= 0; $i--) {
            $date      = now()->subMonths($i);
            $months[]  = $date->translatedFormat('M');
            $amount    = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount') / 1000000;
            $revenue[] = round($amount, 2);
        }

        return [
            'datasets' => [
                [
                    'label'                => 'Revenue (Rp juta)',
                    'data'                 => $revenue,
                    'borderColor'          => 'rgb(109, 40, 217)',
                    'backgroundColor'      => 'rgba(109, 40, 217, 0.08)',
                    'borderWidth'          => 2,
                    'fill'                 => true,
                    'tension'              => 0.45,
                    'pointRadius'          => 3,
                    'pointHoverRadius'     => 5,
                    'pointBackgroundColor' => 'rgb(109, 40, 217)',
                    'pointBorderColor'     => '#fff',
                    'pointBorderWidth'     => 1.5,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'x' => [
                    'grid'  => ['display' => false],
                    'ticks' => ['color' => 'rgba(100,116,139,0.7)', 'font' => ['size' => 11]],
                ],
                'y' => [
                    'grid'        => ['color' => 'rgba(100,116,139,0.08)'],
                    'ticks'       => ['color' => 'rgba(100,116,139,0.7)', 'font' => ['size' => 11]],
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
