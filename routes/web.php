<?php

use App\Http\Controllers\InvoiceController;
use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');

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
