<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Mail\OrderConfirmed;
use App\Mail\OrderStatusUpdated;
use App\Mail\PaymentConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderNotification implements ShouldQueue{
    use InteractsWithQueue;
    public function handle(OrderEvent $event)
    {
        try {
            $order = $event->order->load([
                'user',
                'orderItems.product',
            ]);

            Log::info('SendOrderNotification déclenché pour la commande #' . $order->id . ' avec l\'action: ' . $event->action);

            switch ($event->action) { // ✅ Changé de eventType à action
                case 'created':
                    Mail::to($order->user->email)->send(new OrderConfirmed($order));
                    Log::info('Email de confirmation envoyé pour la commande #' . $order->id);
                    break;

                case 'status_updated':
                    Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
                    Log::info('Email de mise à jour du statut envoyé pour la commande #' . $order->id);
                    break;

                case 'payment_confirmed':
                    Mail::to($order->user->email)->send(new PaymentConfirmation($order));
                    Log::info('Email de confirmation de paiement envoyé pour la commande #' . $order->id);
                    break;

                default:
                    Log::warning('Action non reconnue: ' . $event->action);
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Erreur dans SendOrderNotification pour la commande #' . $event->order->id . ': ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
