<?php

use App\Http\Controllers\InvoiceController;
use App\Livewire\CategoryPage;
use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;


// Page d'accueil
Route::get('/', HomePage::class)->name('home');
// Page de catégorie (ex: /categorie/smartphones)
Route::get('/categorie/{slug}', CategoryPage::class)->name('category.show');

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
