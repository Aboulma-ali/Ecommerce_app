<?php

namespace App\Livewire;

use App\Livewire\Traits\CartActions;
use App\Models\Product;
use Livewire\Component;

class ProductShowPage extends Component
{
    use CartActions;

    public Product $product;
    public int $quantity = 1;

    /**
     * @var string L'URL de l'image actuellement affichée en grand.
     */
    public string $activeImageUrl = '';

    /**
     * Mount est appelé à l'initialisation.
     * On charge le produit et ses images associées.
     */
    public function mount(Product $product)
    {
        $this->product = $product;
        // On charge les relations pour éviter les requêtes N+1 (très important pour la performance)
        $this->product->load('images', 'category');

        // On définit l'image active par défaut : l'image principale du produit.
        if ($this->product->image) {
            $this->activeImageUrl = \Storage::url($this->product->image);
        }
        // Si pas d'image principale, on prend la première de la galerie.
        elseif ($this->product->images->isNotEmpty()) {
            $this->activeImageUrl = \Storage::url($this->product->images->first()->image_path);
        }
    }

    /**
     * Change l'image principale affichée.
     */
    public function selectImage($imagePath)
    {
        $this->activeImageUrl = \Storage::url($imagePath);
        // On émet un événement pour qu'Alpine.js puisse gérer une transition fluide
        $this->dispatch('image-selected');
    }

    // --- Les autres méthodes ne changent pas ---

    public function incrementQuantity() { $this->quantity++; }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCartHandler()
    {
        $this->addToCart($this->product->id, $this->quantity);
    }

    public function render()
    {
        $similarProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('livewire.product-show-page', [
            'similarProducts' => $similarProducts
        ])->layout('layouts.app');
    }
}
