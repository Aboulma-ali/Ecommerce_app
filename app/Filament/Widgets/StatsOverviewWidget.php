<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // 📊 DONNÉES GÉNÉRALES
        $totalRevenue = Order::where('payment_status', 'payé')->sum('total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'en_attente')->count();
        $deliveredOrders = Order::where('status', 'livrée')->count();
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<', 10)->where('stock', '>', 0)->count();
        $outOfStockProducts = Product::where('stock', 0)->count();
        $totalCustomers = User::count();

        // 📅 DONNÉES MENSUELLES
        $currentMonth = now()->month;
        $lastMonth = now()->subMonth()->month;

        $currentMonthRevenue = Order::where('payment_status', 'payé')
            ->whereMonth('created_at', $currentMonth)
            ->sum('total');

        $lastMonthRevenue = Order::where('payment_status', 'payé')
            ->whereMonth('created_at', $lastMonth)
            ->sum('total');

        $currentMonthOrders = Order::whereMonth('created_at', $currentMonth)->count();
        $lastMonthOrders = Order::whereMonth('created_at', $lastMonth)->count();

        // 📈 CALCULS DES VARIATIONS
        $revenueChange = $lastMonthRevenue > 0
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        $ordersChange = $lastMonthOrders > 0
            ? (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100
            : 0;

        // 💳 DONNÉES PAIEMENTS
        $pendingPayments = Order::where('payment_status', 'non_payé')->sum('total');
        $paidPayments = Order::where('payment_status', 'payé')->sum('total');

        return [
            // 💰 CHIFFRE D'AFFAIRES TOTAL
            Stat::make(
                label: '💰 Chiffre d\'affaires total',
                value: number_format($totalRevenue, 0, ',', ' ') . ' XOF'
            )
                ->description(
                    $revenueChange >= 0
                        ? '📈 +' . number_format($revenueChange, 1) . '% par rapport au mois dernier'
                        : '📉 ' . number_format($revenueChange, 1) . '% par rapport au mois dernier'
                )
                ->descriptionIcon(
                    $revenueChange >= 0
                        ? 'heroicon-m-arrow-trending-up'
                        : 'heroicon-m-arrow-trending-down'
                )
                ->color($revenueChange >= 0 ? 'success' : 'danger')
                ->chart([
                    $lastMonthRevenue,
                    $currentMonthRevenue,
                ]),

            // 📦 TOTAL DES COMMANDES
            Stat::make(
                label: '📦 Total des commandes',
                value: number_format($totalOrders, 0, ',', ' ')
            )
                ->description(
                    $ordersChange >= 0
                        ? '📈 +' . number_format($ordersChange, 1) . '% par rapport au mois dernier'
                        : '📉 ' . number_format($ordersChange, 1) . '% par rapport au mois dernier'
                )
                ->descriptionIcon(
                    $ordersChange >= 0
                        ? 'heroicon-m-arrow-trending-up'
                        : 'heroicon-m-arrow-trending-down'
                )
                ->color($ordersChange >= 0 ? 'success' : 'danger')
                ->chart([
                    $lastMonthOrders,
                    $currentMonthOrders,
                ]),

            // ⏳ COMMANDES EN ATTENTE
            Stat::make(
                label: '⏳ Commandes en attente',
                value: number_format($pendingOrders, 0, ',', ' ')
            )
                ->description('✅ ' . number_format($deliveredOrders, 0, ',', ' ') . ' commandes livrées')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 10 ? 'warning' : 'success'),

            // 💳 PAIEMENTS EN ATTENTE
            Stat::make(
                label: '💳 Paiements en attente',
                value: number_format($pendingPayments, 0, ',', ' ') . ' XOF'
            )
                ->description('✅ ' . number_format($paidPayments, 0, ',', ' ') . ' XOF déjà encaissés')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($pendingPayments > 100000 ? 'warning' : 'success'),

            // 📦 GESTION DU STOCK
            Stat::make(
                label: '📦 Total produits',
                value: number_format($totalProducts, 0, ',', ' ')
            )
                ->description(
                    '⚠️ ' . $lowStockProducts . ' en stock faible • ' .
                    '🚫 ' . $outOfStockProducts . ' en rupture'
                )
                ->descriptionIcon('heroicon-m-cube')
                ->color($outOfStockProducts > 0 ? 'danger' : ($lowStockProducts > 0 ? 'warning' : 'success')),

            // 👥 BASE CLIENTS
            Stat::make(
                label: '👥 Total clients',
                value: number_format($totalCustomers, 0, ',', ' ')
            )
                ->description('🎯 Clients enregistrés sur la plateforme')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
