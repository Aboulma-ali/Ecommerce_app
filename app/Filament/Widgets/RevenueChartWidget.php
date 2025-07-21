<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class RevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = '💰 Évolution du Chiffre d\'affaires';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    public ?string $filter = 'last_6_months';

    protected function getData(): array
    {
        $data = $this->getRevenueData();

        return [
            'datasets' => [
                [
                    'label' => 'Chiffre d\'affaires (XOF)',
                    'data' => $data['revenue'],
                    'borderColor' => 'rgb(99, 102, 241)',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Nombre de commandes',
                    'data' => $data['orders'],
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getRevenueData(): array
    {
        $filter = $this->filter;

        switch ($filter) {
            case 'last_7_days':
                return $this->getLast7DaysData();
            case 'last_30_days':
                return $this->getLast30DaysData();
            case 'last_6_months':
                return $this->getLast6MonthsData();
            case 'last_year':
                return $this->getLastYearData();
            default:
                return $this->getLast6MonthsData();
        }
    }

    private function getLast7DaysData(): array
    {
        $labels = [];
        $revenue = [];
        $orders = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayData = Order::where('payment_status', 'payé')
                ->whereDate('created_at', $date)
                ->selectRaw('SUM(total) as revenue, COUNT(*) as order_count')
                ->first();

            $labels[] = $date->format('d/m');
            $revenue[] = $dayData->revenue ?? 0;
            $orders[] = $dayData->order_count ?? 0;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orders,
        ];
    }

    private function getLast30DaysData(): array
    {
        $labels = [];
        $revenue = [];
        $orders = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayData = Order::where('payment_status', 'payé')
                ->whereDate('created_at', $date)
                ->selectRaw('SUM(total) as revenue, COUNT(*) as order_count')
                ->first();

            // Afficher une date sur 5 pour la lisibilité
            $labels[] = $i % 5 === 0 ? $date->format('d/m') : '';
            $revenue[] = $dayData->revenue ?? 0;
            $orders[] = $dayData->order_count ?? 0;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orders,
        ];
    }

    private function getLast6MonthsData(): array
    {
        $labels = [];
        $revenue = [];
        $orders = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthData = Order::where('payment_status', 'payé')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->selectRaw('SUM(total) as revenue, COUNT(*) as order_count')
                ->first();

            $labels[] = $month->format('M Y');
            $revenue[] = $monthData->revenue ?? 0;
            $orders[] = $monthData->order_count ?? 0;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orders,
        ];
    }

    private function getLastYearData(): array
    {
        $labels = [];
        $revenue = [];
        $orders = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthData = Order::where('payment_status', 'payé')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->selectRaw('SUM(total) as revenue, COUNT(*) as order_count')
                ->first();

            $labels[] = $month->format('M Y');
            $revenue[] = $monthData->revenue ?? 0;
            $orders[] = $monthData->order_count ?? 0;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orders,
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'last_7_days' => '7 derniers jours',
            'last_30_days' => '30 derniers jours',
            'last_6_months' => '6 derniers mois',
            'last_year' => '12 derniers mois',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                    'callbacks' => [
                        'label' => "function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.datasetIndex === 0) {
                                label += new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' XOF';
                            } else {
                                label += context.parsed.y + ' commandes';
                            }
                            return label;
                        }"
                    ]
                ]
            ],
            'scales' => [
                'x' => [
                    'display' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Période'
                    ]
                ],
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Chiffre d\'affaires (XOF)'
                    ],
                    'ticks' => [
                        'callback' => "function(value) {
                            return new Intl.NumberFormat('fr-FR').format(value) + ' XOF';
                        }"
                    ]
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Nombre de commandes'
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ]
            ],
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ]
        ];
    }
}
