<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';
    public ?string $status = null; // Propriété pour stocker le message de succès

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $validated = $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink($validated);

        if ($status === Password::RESET_LINK_SENT) {
            $this->status = __($status); // Stocker le message de succès
            return;
        }

        // Si l'envoi échoue, ajouter l'erreur au champ email
        $this->addError('email', __($status));
    }
}; ?>

    <!-- La page commence directement par la carte, qui est insérée dans le layout 'guest' -->
<div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-2">

    <!-- Colonne de gauche : Formulaire -->
    <div class="p-8 sm:p-12 order-2 lg:order-1 flex flex-col justify-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" wire:navigate class="flex items-center group mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-2 rounded-xl mr-3">
                <x-application-logo class="block h-8 w-auto fill-current text-white" />
            </div>
            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                ShopVibe
            </span>
        </a>

        <!-- Titre -->
        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-gray-900">Mot de passe oublié ?</h1>
            <p class="mt-2 text-gray-600">Pas de souci. Indiquez votre email et nous vous enverrons un lien pour le réinitialiser.</p>
        </div>

        <!-- Message de succès (Status) -->
        @if ($status)
            <div class="mb-4 font-medium text-sm text-green-700 bg-green-100 p-4 rounded-lg">
                {{ $status }}
            </div>
        @else
            <!-- Formulaire (affiché seulement si pas de message de succès) -->
            <form wire:submit="sendPasswordResetLink" class="space-y-6">
                <!-- Adresse Email -->
                <div>
                    <label for="email" class="text-sm font-medium text-gray-700">Votre adresse email</label>
                    <div class="mt-1 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg></span>
                        <input wire:model="email" id="email" type="email" required autofocus placeholder="votre.email@exemple.com"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    @error('email') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                </div>

                <!-- Bouton de soumission -->
                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-lg font-bold text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:shadow-lg hover:scale-105 transform transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span wire:loading.remove wire:target="sendPasswordResetLink">Envoyer le lien</span>
                        <span wire:loading wire:target="sendPasswordResetLink">Envoi en cours...</span>
                    </button>
                </div>
            </form>
        @endif

        <!-- Lien pour retourner à la connexion -->
        <p class="mt-8 text-center text-sm text-gray-600">
            <a href="{{ route('login') }}" wire:navigate class="font-medium text-blue-600 hover:text-blue-500">
                ← Retour à la connexion
            </a>
        </p>
    </div>

    <!-- Colonne de droite : Visuel -->
    <div class="hidden lg:flex items-center justify-center p-8 bg-gradient-to-br from-blue-600 to-purple-600 order-1 lg:order-2">
        <div class="text-white text-center">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/forgot-password-4268397-3551744.png" alt="Illustration Mot de passe oublié" class="w-full max-w-sm mx-auto mb-8">
            <h2 class="text-3xl font-bold mb-3">Retrouvez l'accès à votre compte</h2>
            <p class="text-blue-200">Suivez les instructions simples que nous allons vous envoyer par email pour choisir un nouveau mot de passe sécurisé.</p>
        </div>
    </div>
</div>
