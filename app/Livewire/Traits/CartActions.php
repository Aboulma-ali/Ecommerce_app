<?php

namespace App\Livewire\Traits;

use App\Models\Product;

trait CartActions
{
    public function getCartInstance()
    {
        return session()->get('cart', collect());
    }

    public function updateCart($cart)
    {
        session()->put('cart', $cart);
        $this->dispatch('cartUpdated');
    }

    public function addToCart($productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);
        $cart = $this->getCartInstance();

        if ($cart->has($productId)) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image, // Assurez-vous d'avoir un accesseur pour l'URL complète
                'quantity' => $quantity,
            ];
        }

        $this->updateCart($cart);
        $this->dispatch('notify', message: "{$product->name} a été ajouté au panier !");
    }

    public function removeFromCart($productId)
    {
        $cart = $this->getCartInstance();
        $cart->forget($productId);
        $this->updateCart($cart);
        $this->dispatch('notify', message: 'Produit retiré du panier.');
    }

    public function updateQuantity($productId, $quantity)
    {
        $cart = $this->getCartInstance();

        if ($cart->has($productId)) {
            if ($quantity > 0) {
                // 1. On récupère l'élément existant
                $item = $cart->get($productId);

                // 2. On met à jour la quantité dans cet élément
                $item['quantity'] = $quantity;

                // 3. On remplace l'ancien élément par le nouveau dans la collection
                $cart->put($productId, $item);
            } else {
                // Si la quantité est de 0 ou moins, on retire le produit
                $cart->forget($productId);
            }
        }

        // On sauvegarde la collection entièrement mise à jour dans la session
        $this->updateCart($cart);
    }

    public function clearCart()
    {
        $this->updateCart(collect());
        $this->dispatch('notify', message: 'Le panier a été vidé.');
    }
}
