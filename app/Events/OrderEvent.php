<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    public string $action; // ✅ Ajout de la propriété manquante

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, string $action)
    {
        $this->order = $order;
        $this->action = $action; // ✅ Initialisation de la propriété
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
