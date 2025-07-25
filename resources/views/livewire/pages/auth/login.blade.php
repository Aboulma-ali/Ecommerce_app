<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }
}; ?>

    <!-- Le code commence DIRECTEMENT par la carte de connexion, qui définit sa propre taille et son style -->
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
            <h1 class="text-3xl font-extrabold text-gray-900">Content de vous revoir !</h1>
            <p class="mt-2 text-gray-600">Connectez-vous pour continuer votre shopping.</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-md">
                {{ session('status') }}
            </div>
        @endif

        <!-- Formulaire -->
        <form wire:submit="login" class="space-y-6">
            <!-- Adresse Email -->
            <div>
                <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg></span>
                    <input wire:model="form.email" id="email" type="email" required autofocus autocomplete="username" placeholder="votre.email@exemple.com"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                @error('form.email') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
            </div>
            <!-- Mot de passe -->
            <div>
                <label for="password" class="text-sm font-medium text-gray-700">Mot de passe</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></span>
                    <input wire:model="form.password" id="password" type="password" required autocomplete="current-password" placeholder="••••••••"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                @error('form.password') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
            </div>
            <!-- Options -->
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center"><input wire:model="form.remember" id="remember" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"><label for="remember" class="ml-2 block text-gray-900">Se souvenir de moi</label></div>
                @if (Route::has('password.request'))<a class="font-medium text-blue-600 hover:text-blue-500" href="{{ route('password.request') }}" wire:navigate>Mot de passe oublié ?</a>@endif
            </div>
            <!-- Bouton de soumission -->
            <div><button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-lg font-bold text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:shadow-lg hover:scale-105 transform transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"><span wire:loading.remove wire:target="login">Se connecter</span><span wire:loading wire:target="login">Connexion...</span></button></div>
        </form>
        <!-- Lien vers l'inscription -->
        <p class="mt-10 text-center text-sm text-gray-600">Pas encore de compte ? <a href="{{ route('register') }}" wire:navigate class="font-medium text-blue-600 hover:text-blue-500">Inscrivez-vous ici</a></p>
    </div>

    <!-- Colonne de droite : Visuel -->
    <div class="hidden lg:flex items-center justify-center p-8 bg-gradient-to-br from-blue-600 to-purple-600 order-1 lg:order-2">
        <div class="text-white text-center">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/secure-login-5120700-4283469.png" alt="Illustration de connexion" class="w-full max-w-sm mx-auto mb-8">
            <h2 class="text-3xl font-bold mb-3">Une expérience shopping exclusive</h2>
            <p class="text-blue-200">Accédez à vos commandes, vos favoris et des offres personnalisées en un seul clic.</p>
        </div>
    </div>
</div>
