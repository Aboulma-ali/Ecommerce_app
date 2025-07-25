<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        // Redirige vers la page d'accueil, ce qui est plus logique pour un site e-commerce
        $this->redirect(route('home', absolute: false), navigate: true);
    }
}; ?>

    <!-- La page commence directement par la carte, qui est insérée dans le layout 'guest' -->
<div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-2">

    <!-- Colonne de gauche : Formulaire -->
    <div class="p-8 sm:p-12 order-2 lg:order-1">
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
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Créez votre compte</h1>
            <p class="mt-2 text-gray-600">Rejoignez-nous ! C'est simple, rapide et plein d'avantages.</p>
        </div>

        <!-- Formulaire -->
        <form wire:submit="register" class="space-y-5">
            <!-- Nom complet -->
            <div>
                <label for="name" class="text-sm font-medium text-gray-700">Nom complet</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></span>
                    <input wire:model="name" id="name" type="text" required autofocus autocomplete="name" placeholder="ex: Jean Dupont" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                @error('name') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
            </div>

            <!-- Adresse Email -->
            <div>
                <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg></span>
                    <input wire:model="email" id="email" type="email" required autocomplete="username" placeholder="votre.email@exemple.com" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                @error('email') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
            </div>

            <!-- Mot de passe -->
            <div>
                <label for="password" class="text-sm font-medium text-gray-700">Mot de passe</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></span>
                    <input wire:model="password" id="password" type="password" required autocomplete="new-password" placeholder="••••••••" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                @error('password') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
            </div>

            <!-- Confirmation du mot de passe -->
            <div>
                <label for="password_confirmation" class="text-sm font-medium text-gray-700">Confirmez le mot de passe</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></span>
                    <input wire:model="password_confirmation" id="password_confirmation" type="password" required autocomplete="new-password" placeholder="••••••••" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>

            <!-- Bouton de soumission -->
            <div class="pt-4">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-lg font-bold text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:shadow-lg hover:scale-105 transform transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span wire:loading.remove wire:target="register">Créer mon compte</span>
                    <span wire:loading wire:target="register">Création...</span>
                </button>
            </div>
        </form>

        <!-- Lien vers la connexion -->
        <p class="mt-8 text-center text-sm text-gray-600">
            Déjà un compte ?
            <a href="{{ route('login') }}" wire:navigate class="font-medium text-blue-600 hover:text-blue-500">
                Connectez-vous
            </a>
        </p>
    </div>

    <!-- Colonne de droite : Visuel avec les avantages -->
    <div class="hidden lg:flex items-center justify-center p-8 bg-gradient-to-br from-blue-600 to-purple-600 order-1 lg:order-2">
        <div class="text-white">
            <h2 class="text-3xl font-bold mb-6 text-center">Vos avantages exclusifs</h2>
            <ul class="space-y-6">
                <li class="flex items-start">
                    <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-white/20"><svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-4a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span></div>
                    <div class="ml-4">
                        <h3 class="font-semibold">Checkout express</h3>
                        <p class="text-blue-200 text-sm">Sauvegardez vos informations pour des achats plus rapides.</p>
                    </div>
                </li>
                <li class="flex items-start">
                    <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-white/20"><svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"></path></svg></span></div>
                    <div class="ml-4">
                        <h3 class="font-semibold">Historique des commandes</h3>
                        <p class="text-blue-200 text-sm">Suivez vos achats et consultez vos anciennes commandes facilement.</p>
                    </div>
                </li>
                <li class="flex items-start">
                    <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-white/20"><svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg></span></div>
                    <div class="ml-4">
                        <h3 class="font-semibold">Liste de favoris</h3>
                        <p class="text-blue-200 text-sm">Enregistrez les articles que vous aimez pour plus tard.</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
