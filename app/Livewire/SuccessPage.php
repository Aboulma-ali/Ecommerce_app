<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SuccessPage extends Component
{
    // 🔑 Déclaration obligatoire de la propriété publique
    public $order;

    public function render()
    {
        try {
            // ✅ CORRECTION PRINCIPALE : structure de la requête
            $this->order = Order::with('shippingAddress') // 👈 Nom de relation CORRECT
            ->where('user_id', Auth::id())
                ->latest('ordered_at') // 👈 Colonne de tri correcte
                ->firstOrFail(); // 👈 Gestion propre des erreurs
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // 🛑 Gestion sécurisée si aucune commande n'existe
            session()->flash('error', 'Aucune commande trouvée. Veuillez contacter le support.');
            return redirect()->route('cart.index');
        }

        return view('livewire.success-page')->layout('layouts.app');
    }
}
