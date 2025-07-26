<?php

namespace App\Livewire;

use App\Livewire\Traits\CartActions;
use Livewire\Component;

class CartPage extends Component
{
    use CartActions;

    public $cartItems = [];

    // Propriétés calculées pour le total
    public function getSubtotalProperty()
    {
        return collect($this->cartItems)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });
    }

    public function getTotalProperty()
    {
        // Pour l'instant, identique au sous-total. Pourrait inclure taxes, etc.
        return $this->subtotal;
    }

    public function mount()
    {
        $this->cartItems = $this->getCartInstance()->toArray();
    }

    // On doit écouter aussi ici pour rafraîchir la page si on est dessus
    protected $listeners = ['cartUpdated' => 'refreshCart'];

    public function refreshCart()
    {
        $this->cartItems = $this->getCartInstance()->toArray();
    }

    public function incrementQuantity($productId)
    {
        $cart = $this->getCartInstance();
        if ($cart->has($productId)) {
            $this->updateQuantity($productId, $cart[$productId]['quantity'] + 1);
            $this->refreshCart();
        }
    }

    public function decrementQuantity($productId)
    {
        $cart = $this->getCartInstance();
        if ($cart->has($productId) && $cart[$productId]['quantity'] > 1) {
            $this->updateQuantity($productId, $cart[$productId]['quantity'] - 1);
            $this->refreshCart();
        }
    }

    public function clearCart()
    {
        // On réutilise la méthode du Trait
        parent::clearCart();
        $this->refreshCart();
    }

    public function render()
    {
        return view('livewire.cart-page')->layout('layouts.app');
    }
}
