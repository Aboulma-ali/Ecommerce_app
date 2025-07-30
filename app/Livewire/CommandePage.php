<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class CommandePage extends Component
{
    use WithPagination;

    public ?Order $selectedOrder = null;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function viewOrder(int $orderId): void
    {
        // Vérifier que la commande appartient bien à l'utilisateur connecté
        $this->selectedOrder = Order::where('user_id', auth()->id())
            ->with(['user', 'items.product', 'shippingAddress'])
            ->findOrFail($orderId);
    }

    public function showList(): void
    {
        $this->selectedOrder = null;
        $this->resetPage();
    }

    public function render()
    {
        // Récupérer uniquement les commandes de l'utilisateur connecté
        $orders = is_null($this->selectedOrder)
            ? Order::where('user_id', auth()->id())
                ->with('user')
                ->latest()
                ->paginate(10)
            : collect();

        return view('livewire.commande-page', [
            'orders' => $orders,
        ])->layout('layouts.app');
    }
}
