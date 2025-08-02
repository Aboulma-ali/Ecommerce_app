<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class ProductIndexPage extends Component
{
    // Propriétés publiques pour les données
    public $products;
    public $categories;

    // Propriétés pour les filtres
    public $selectedCategories = [];
    public $inStock = false;
    public $onSale = false;
    public $priceRange = 500000;
    public $sortBy = 'latest';

    public function mount()
    {
        // Initialiser les données lors du montage du composant
        $this->categories = Category::all();
        $this->loadProducts();
    }

    public function loadProducts()
    {
        // Commencer avec tous les produits
        $query = Product::with('category');

        // Filtrer par catégories sélectionnées
        if (!empty($this->selectedCategories)) {
            $query->whereIn('category_id', $this->selectedCategories);
        }

        // Filtrer par stock
        if ($this->inStock) {
            $query->where('stock', '>', 0);
        }


        // Filtrer par prix
        if ($this->priceRange < 500000) {
            $query->where('price', '<=', $this->priceRange);
        }

        // Trier les résultats
        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $this->products = $query->get();
    }

    // Méthode appelée quand les catégories sélectionnées changent
    public function updatedSelectedCategories()
    {
        $this->loadProducts();
    }

    // Méthode appelée quand le filtre stock change
    public function updatedInStock()
    {
        $this->loadProducts();
    }

    // Méthode appelée quand le filtre promotion change
    public function updatedOnSale()
    {
        $this->loadProducts();
    }

    // Méthode appelée quand le range de prix change
    public function updatedPriceRange()
    {
        $this->loadProducts();
    }

    // Méthode appelée quand le tri change
    public function updatedSortBy()
    {
        $this->loadProducts();
    }

    // Méthode pour réinitialiser tous les filtres
    public function resetFilters()
    {
        $this->selectedCategories = [];
        $this->inStock = false;
        $this->onSale = false;
        $this->priceRange = 500000;
        $this->sortBy = 'latest';
        $this->loadProducts();
    }

    public function render()
    {
        return view('livewire.product.product-index-page', [
            'products' => $this->products,
            'categories' => $this->categories
        ])->layout('layouts.app');
    }
}
