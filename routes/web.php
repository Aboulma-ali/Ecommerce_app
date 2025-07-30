<?php

use App\Http\Controllers\InvoiceController;
use App\Livewire\CancelPage;
use App\Livewire\CategoryPage;
use App\Livewire\CheckoutPage;
use App\Livewire\CommandePage;
use App\Livewire\HomePage;
use App\Livewire\Product\ProductIndexPage;
use App\Livewire\SuccessPage;
use Illuminate\Support\Facades\Route;
use App\Livewire\ProductShowPage;
use App\Livewire\CartPage;

/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
| Ces routes sont accessibles à tout le monde, même aux visiteurs non connectés.
*/
Route::get('/', HomePage::class)->name('home');
Route::get('/produits', ProductIndexPage::class)->name('products.index');
Route::get('/product/{product}', ProductShowPage::class)->name('product.show');
Route::get('/categorie/{slug}', CategoryPage::class)->name('category.show');

// La page du panier est techniquement publique, mais ne contiendra rien pour un visiteur.
Route::get('/cart', CartPage::class)->name('cart.index');


/*
|--------------------------------------------------------------------------
| Routes d'Authentification
|--------------------------------------------------------------------------
| Gère le login, l'inscription, la réinitialisation de mot de passe, etc.
*/
require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| Routes Protégées pour les Clients Connectés
|--------------------------------------------------------------------------
| Toutes ces routes nécessitent que l'utilisateur soit connecté.
| On utilise un groupe de middlewares pour l'appliquer à toutes en même temps.
*/
Route::middleware(['auth'])->group(function () {

    Route::view('profile', 'profile')->name('profile');

    // Tunnel de commande
    Route::get('/checkout', CheckoutPage::class)->name('checkout');
    Route::get('/success', SuccessPage::class)->name('success.index');
    Route::get('/cancel', CancelPage::class)->name('cancel.index');

    // Gestion des commandes du client
    Route::get('/commande', CommandePage::class)->name('commande.index');
    Route::get('/orders/{order}/invoice', [InvoiceController::class, 'download'])->name('orders.invoice');
    Route::get('/invoice/{order}/download', [InvoiceController::class, 'download'])->name('invoice.download');

    // Vous pourriez ajouter ici une route pour le "dashboard" client si vous en créez un
    // Route::view('dashboard', 'dashboard')->name('dashboard');

});


/*
|--------------------------------------------------------------------------
| Routes pour les Administrateurs
|--------------------------------------------------------------------------
| La protection de ces routes ne se fait PAS ici, mais directement dans
| le Panel Provider de Filament (app/Providers/Filament/AdminPanelProvider.php).
| C'est la méthode recommandée par Filament.
*/
// Le fichier de routes pour Filament est généralement géré par le package lui-même.
// Vous n'avez rien à ajouter ici pour que '/admin' fonctionne.
