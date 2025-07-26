<?php

use App\Http\Controllers\InvoiceController;
use App\Livewire\CategoryPage;
use App\Livewire\HomePage;
use App\Livewire\Product\ProductIndexPage;
use Illuminate\Support\Facades\Route;
use App\Livewire\ProductShowPage;


// Page d'accueil
Route::get('/', HomePage::class)->name('home');
// Page de catégorie (ex: /categorie/smartphones)
Route::get('/categorie/{slug}', CategoryPage::class)->name('category.show');

// Route pour la liste de tous les produits
Route::get('/produits', ProductIndexPage::class)->name('products.index');

// Route pour la page de détail d'un produit (avec route-model binding sur le slug)
Route::get('/produits/{slug}', ProductShowPage::class)->name('product.show');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::get('/orders/{order}/invoice', [InvoiceController::class, 'download'])
    ->name('orders.invoice')
    ->middleware('auth');
