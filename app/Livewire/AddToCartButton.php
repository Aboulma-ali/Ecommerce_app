<?php

namespace App\Livewire;

use App\Livewire\Traits\CartActions;
use App\Models\Product;
use Livewire\Component;

class AddToCartButton extends Component
{
    use CartActions; // On importe notre logique !

    public Product $product;

    public function add()
    {
        $this->addToCart($this->product->id);
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
}
