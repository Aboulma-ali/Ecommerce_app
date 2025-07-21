<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecentOrdersWidget extends ChartWidget
{
    protected static ?string $heading = '📋 Commandes Récentes (7 derniers jours)';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Récupérer les données des 7 derniers jours
        $orders = Order::query()
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw("SUM(CASE WHEN payment_status IN ('payé', 'paid', 'paye') THEN 1 ELSE 0 END) as paid_orders"),
                DB::raw("SUM(CASE WHEN status IN ('livrée', 'delivered', 'livree') THEN 1 ELSE 0 END) as delivered_orders")
            ])
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Générer les 7 derniers jours
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(Carbon::now()->subDays($i)->format('Y-m-d'));
        }

        // Mapper les données avec les dates
        $mappedData = $dates->map(function ($date) use ($orders) {
            $dayData = $orders->firstWhere('date', $date);
            return [
                'date' => Carbon::parse($date)->format('d/m'),
                'total_orders' => $dayData ? $dayData->total_orders : 0,
                'total_revenue' => $dayData ? $dayData->total_revenue : 0,
                'paid_orders' => $dayData ? $dayData->paid_orders : 0,
                'delivered_orders' => $dayData ? $dayData->delivered_orders : 0,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Commandes totales',
                    'data' => $mappedData->pluck('total_orders')->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Commandes payées',
                    'data' => $mappedData->pluck('paid_orders')->toArray(),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                    'borderColor' => 'rgba(16, 185, 129, 1)',
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Commandes livrées',
                    'data' => $mappedData->pluck('delivered_orders')->toArray(),
                    'backgroundColor' => 'rgba(245, 158, 11, 0.8)',
                    'borderColor' => 'rgba(245, 158, 11, 1)',
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $mappedData->pluck('date')->toArray(),
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
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                    'callbacks' => [
                        'afterLabel' => 'function(context) {
                            if (context.datasetIndex === 0) {
                                return "CA: " + new Intl.NumberFormat("fr-FR", {
                                    style: "currency",
                                    currency: "XOF"
                                }).format(context.parsed.revenue || 0);
                            }
                            return "";
                        }',
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jours',
                    ],
                ],
                'y' => [
                    'display' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Nombre de commandes',
                    ],
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'hover' => [
                'mode' => 'index',
                'intersect' => false,
            ],
        ];
    }

    // Méthode pour obtenir les statistiques supplémentaires
    protected function getStats(): array
    {
        $stats = Order::query()
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->selectRaw("
                COUNT(*) as total_orders,
                SUM(total) as total_revenue,
                AVG(total) as avg_order_value,
                SUM(CASE WHEN payment_status IN ('payé', 'paid', 'paye') THEN 1 ELSE 0 END) as paid_orders,
                SUM(CASE WHEN status IN ('livrée', 'delivered', 'livree') THEN 1 ELSE 0 END) as delivered_orders
            ")
            ->first();

        return [
            'total_orders' => $stats->total_orders ?? 0,
            'total_revenue' => $stats->total_revenue ?? 0,
            'avg_order_value' => $stats->avg_order_value ?? 0,
            'paid_orders' => $stats->paid_orders ?? 0,
            'delivered_orders' => $stats->delivered_orders ?? 0,
            'payment_rate' => $stats->total_orders > 0 ? round(($stats->paid_orders / $stats->total_orders) * 100, 1) : 0,
            'delivery_rate' => $stats->total_orders > 0 ? round(($stats->delivered_orders / $stats->total_orders) * 100, 1) : 0,
        ];
    }
}
