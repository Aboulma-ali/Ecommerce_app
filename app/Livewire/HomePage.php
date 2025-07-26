<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Collection; // <-- Ajoutez cette ligne
use App\Models\Category;
use Livewire\Component;

class HomePage extends Component
{
    public Collection $categories;
    public Collection $featuredProducts;
    public function render()
    {
        $this->categories = Category::all();
        $this->featuredProducts = Product::latest()->take(4)->get();
        return view('livewire.home-page')
            ->layout('layouts.app');

    }
}
