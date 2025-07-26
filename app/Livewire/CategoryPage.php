<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryPage extends Component
{
    use WithPagination;

    public Category $category;

    // Options de tri
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Options de filtre par PRIX (AUCUNE MARQUE ICI)
    public int $maxPrice;
    public int $priceRange = 0;

    public function mount(string $slug): void
    {
        $this->category = Category::where('slug', $slug)->firstOrFail();

        // On initialise le filtre de prix avec le prix maximum trouvé dans cette catégorie
        $this->maxPrice = Product::where('category_id', $this->category->id)->max('price') ?? 1000;
        $this->priceRange = $this->maxPrice;
    }

    public function updating($key): void
    {
        if (in_array($key, ['sortField', 'sortDirection', 'priceRange'])) {
            $this->resetPage();
        }
    }

    public function setSort(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = ($field === 'price') ? 'asc' : 'desc';
        }
    }

    public function render()
    {
        $productsQuery = Product::where('category_id', $this->category->id);

        // On applique le filtre par tranche de prix
        if ($this->priceRange > 0) {
            $productsQuery->where('price', '<=', $this->priceRange);
        }

        // On applique le tri
        $productsQuery->orderBy($this->sortField, $this->sortDirection);

        $products = $productsQuery->paginate(12);

        return view('livewire.category-page', [
            'products' => $products,
        ])->layout('layouts.app');
    }
}
