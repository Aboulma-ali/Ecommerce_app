<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">
            Changer de mot de passe
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Utilisez un mot de passe long et aléatoire pour garantir la sécurité de votre compte.
        </p>
    </header>

    <form wire:submit="updatePassword" class="mt-6 space-y-6">

        <!-- Mot de passe actuel -->
        <div>
            <label for="update_password_current_password" class="text-sm font-medium text-gray-700">Mot de passe actuel</label>
            <div class="mt-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.629 5.265l-2.971 2.971a2 2 0 11-2.828-2.828l2.971-2.971A6 6 0 0121 11z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </span>
                <input wire:model="current_password" id="update_password_current_password" type="password" class="block w-full max-w-lg pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" autocomplete="current-password">
            </div>
            @error('current_password') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
        </div>

        <!-- Nouveau mot de passe -->
        <div>
            <label for="update_password_password" class="text-sm font-medium text-gray-700">Nouveau mot de passe</label>
            <div class="mt-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                   <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </span>
                <input wire:model="password" id="update_password_password" type="password" class="block w-full max-w-lg pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" autocomplete="new-password">
            </div>
            @error('password') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
        </div>

        <!-- Confirmer le nouveau mot de passe -->
        <div>
            <label for="update_password_password_confirmation" class="text-sm font-medium text-gray-700">Confirmer le nouveau mot de passe</label>
            <div class="mt-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                   <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </span>
                <input wire:model="password_confirmation" id="update_password_password_confirmation" type="password" class="block w-full max-w-lg pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" autocomplete="new-password">
            </div>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:scale-105 transition-transform">
                Mettre à jour le mot de passe
            </button>

            @if (session()->has('status') && session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-green-600 font-medium">
                    ✔ Mot de passe mis à jour.
                </p>
            @endif
        </div>
    </form>
</section>
