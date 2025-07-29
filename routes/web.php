<?php

use App\Http\Controllers\InvoiceController;
use App\Livewire\CancelPage;
use App\Livewire\CategoryPage;
use App\Livewire\CheckoutPage;
use App\Livewire\HomePage;
use App\Livewire\Product\ProductIndexPage;
use App\Livewire\SuccessPage;
use Illuminate\Support\Facades\Route;
use App\Livewire\ProductShowPage;
use App\Livewire\CartPage;


// Page d'accueil
Route::get('/', HomePage::class)->name('home');
// Page de catégorie (ex: /categorie/smartphones)
Route::get('/categorie/{slug}', CategoryPage::class)->name('category.show');

// Route pour la liste de tous les produits
Route::get('/produits', ProductIndexPage::class)->name('products.index');

Route::get('/product/{product}', ProductShowPage::class)->name('product.show');

Route::get('/cart', CartPage::class)->name('cart.index');

Route::get('/checkout', CheckoutPage::class)->name('checkout')->middleware('auth');

Route::get('/success', SuccessPage::class)->name('success.index')->middleware('auth');

Route::get('/cancel', CancelPage::class)->name('cancel.index')->middleware('auth');

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
//Route::get('/invoices',InvoiceController::class)->name('invoices.index')->middleware('auth');
Route::get('/invoice/{order}/download', [InvoiceController::class, 'download'])->name('invoice.download');
