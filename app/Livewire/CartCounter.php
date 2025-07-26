<?php

namespace App\Livewire;

use Livewire\Component;

class CartCounter extends Component
{
    public $count = 0;

    // On écoute l'événement global 'cartUpdated'
    protected $listeners = ['cartUpdated' => 'updateCount'];

    public function mount()
    {
        $this->updateCount();
    }

    public function updateCount()
    {
        $this->count = session()->get('cart', collect())->sum('quantity');
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}
