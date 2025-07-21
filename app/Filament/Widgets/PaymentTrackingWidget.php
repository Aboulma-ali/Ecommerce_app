<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PaymentTrackingWidget extends ChartWidget
{
    protected static ?string $heading = '💳 Répartition des Paiements';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    public ?string $filter = 'all_time';

    protected function getData(): array
    {
        $data = $this->getPaymentData();

        return [
            'datasets' => [
                [
                    'data' => array_values($data['amounts']),
                    'backgroundColor' => [
                        'rgba(34, 197, 94, 0.8)',   // Vert pour payé
                        'rgba(239, 68, 68, 0.8)',   // Rouge pour non payé
                    ],
                    'borderColor' => [
                        'rgb(34, 197, 94)',
                        'rgb(239, 68, 68)',
                    ],
                    'borderWidth' => 2,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => array_keys($data['amounts']),
        ];
    }

    protected function getPaymentData(): array
    {
        $query = Order::query();

        // Appliquer le filtre temporel
        switch ($this->filter) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', now()->subMonth()->month)
                    ->whereYear('created_at', now()->subMonth()->year);
                break;
        }

        $paymentData = $query
            ->select([
                'payment_status',
                DB::raw('SUM(total) as total_amount'),
                DB::raw('COUNT(*) as order_count')
            ])
            ->groupBy('payment_status')
            ->get();

        $amounts = [];
        $counts = [];

        // Initialiser avec 0
        $amounts['Payé'] = 0;
        $amounts['Non Payé'] = 0;
        $counts['Payé'] = 0;
        $counts['Non Payé'] = 0;

        foreach ($paymentData as $payment) {
            $label = $payment->payment_status === 'payé' ? 'Payé' : 'Non Payé';
            $amounts[$label] = (float) $payment->total_amount;
            $counts[$label] = (int) $payment->order_count;
        }

        return [
            'amounts' => $amounts,
            'counts' => $counts,
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'all_time' => 'Tout le temps',
            'today' => 'Aujourd\'hui',
            'this_week' => 'Cette semaine',
            'this_month' => 'Ce mois-ci',
            'last_month' => 'Le mois dernier',
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 20,
                    ],
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + new Intl.NumberFormat('fr-FR').format(context.parsed) + ' XOF (' + percentage + '%)';
                        }"
                    ]
                ]
            ],
            'cutout' => '50%',
        ];
    }
}
