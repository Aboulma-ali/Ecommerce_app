<?php

namespace App\Observers;

use App\Events\OrderEvent;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Gérer l'événement "created" pour le modèle Order.
     */
    public function created(Order $order): void
    {
        try {
            if (!$order) {
                Log::error('Record non défini dans OrderObserver (created)');
                return;
            }

            event(new OrderEvent($order, 'created'));
            Log::info('Événement OrderEvent déclenché pour la commande #' . $order->id . ' avec l\'action: created');
        } catch (\Exception $e) {
            Log::error('Erreur dans OrderObserver (created) pour la commande #' . ($order->id ?? 'inconnu') . ': ' . $e->getMessage());
            if (config('app.debug')) {
                Log::debug('Stack trace: ' . $e->getTraceAsString());
            }
        }
    }

    /**
     * Gérer l'événement "saved" pour le modèle Order.
     */
    public function saved(Order $order): void
    {
        try {
            if (!$order) {
                Log::error('Record non défini dans OrderObserver (saved)');
                return;
            }

            // Déclencher un événement si le statut de la commande a changé
            if ($order->wasChanged('status')) {
                event(new OrderEvent($order, 'status_updated'));
                Log::info('Événement OrderEvent déclenché pour la commande #' . $order->id . ' avec l\'action: status_updated, nouveau statut: ' . $order->status);
            }

            // Déclencher un événement si le statut de paiement passe à 'payé'
            if ($order->wasChanged('payment_status') && $order->payment_status === 'payé') {
                event(new OrderEvent($order, 'payment_confirmed'));
                Log::info('Événement OrderEvent déclenché pour la commande #' . $order->id . ' avec l\'action: payment_confirmed');
            }
        } catch (\Exception $e) {
            Log::error('Erreur dans OrderObserver (saved) pour la commande #' . ($order->id ?? 'inconnu') . ': ' . $e->getMessage());
            if (config('app.debug')) {
                Log::debug('Stack trace: ' . $e->getTraceAsString());
            }
        }
    }
}
