<?php

use App\Livewire\Actions\Logout;
use App\Models\Category;
use Livewire\Volt\Component;

new class extends Component
{
    public $categories;

    public function mount(): void
    {
        // On récupère les 5 premières catégories pour le menu
        $this->categories = Category::query()->take(5)->get();
    }

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-lg border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" wire:navigate class="flex items-center group">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-2 rounded-xl mr-3 group-hover:shadow-lg group-hover:scale-105 transition-all duration-300">
                            <x-application-logo class="block h-8 w-auto fill-current text-white" />
                        </div>
                        <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            ShopVibe
                        </span>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-2 sm:ml-10 sm:flex">
                    <a href="{{ route('home') }}" wire:navigate class="px-4 py-2 rounded-lg text-gray-700 hover:text-blue-600 hover:bg-blue-50/70 transition-all duration-200 font-medium relative group">
                        Accueil
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></span>
                    </a>

                    <!-- ==================================================== -->
                    <!--     MEGA MENU POUR LES CATÉGORIES (COMPLET)        -->
                    <!-- ==================================================== -->
                    <div class="relative group">
                        <button class="px-4 py-2 rounded-lg text-gray-700 hover:text-blue-600 hover:bg-blue-50/70 transition-all duration-200 font-medium inline-flex items-center group">
                            Catégories
                            <svg class="ml-1 h-4 w-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="absolute top-full left-1/2 -translate-x-1/2 mt-3 w-screen max-w-md opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:mt-1 transition-all duration-300 z-20">
                            <div class="bg-white rounded-xl shadow-2xl border border-gray-100 p-2">
                                @if($categories->isNotEmpty())
                                    @foreach($categories as $category)
                                        {{-- Assurez-vous d'avoir une colonne 'slug' sur votre table Category et une route 'category.show' --}}
                                        <a href="{{route('category.show', $category->slug) }}" wire:navigate class="flex items-center gap-x-3.5 p-3 rounded-lg hover:bg-gray-100 transition-colors">
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $category->name }}</p>
                                                {{-- <p class="text-sm text-gray-500">{{ $category->description }}</p> --}}
                                            </div>
                                        </a>
                                    @endforeach
                                @else
                                    <p class="p-4 text-sm text-gray-500 text-center">Aucune catégorie.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('products.index') }}" wire:navigate class="px-4 py-2 rounded-lg text-gray-700 hover:text-blue-600 hover:bg-blue-50/70 transition-all duration-200 font-medium relative group">
                        Nos Produits
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></span>
                    </a>
                </div>
            </div>

            <!-- Search Bar (Desktop) -->
            <div class="hidden md:flex flex-1 max-w-xs xl:max-w-lg mx-8 items-center">
                {{-- Ici, vous pourriez mettre un composant de recherche livewire plus tard --}}
                <div class="relative w-full">
                    <input type="text" placeholder="Rechercher des produits..." class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white focus:bg-white shadow-inner focus:shadow-md">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></div>
                </div>
            </div>

            <!-- Right Side Actions -->
            <div class="hidden sm:flex sm:items-center sm:space-x-2">
                <livewire:cart-counter />
                @auth
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger"><button class="flex items-center space-x-2 p-1.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full hover:shadow-lg hover:scale-105 transition-all duration-200 group"><div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center ring-2 ring-white/50"><span class="text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span></div><span class="font-medium pr-2">{{ explode(' ', auth()->user()->name)[0] }}</span></button></x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-gray-100"><div class="font-medium text-base text-gray-800">{{ auth()->user()->name }}</div><div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div></div>
                            <div class="py-1">
                                <x-dropdown-link :href="route('profile')" wire:navigate>Mon Profil</x-dropdown-link>
                                <x-dropdown-link href="{{route('commande.index')}}">Mes Commandes</x-dropdown-link>
                                @role('Admin')
                                <div class="border-t border-gray-100"></div>
                                <x-dropdown-link href="/admin"><span class="font-semibold text-blue-600">Accès Dashboard</span></x-dropdown-link>
                                @endrole
                            </div>
                            <div class="border-t border-gray-100 py-1"><button wire:click="logout" class="w-full text-left block px-4 py-2 text-sm leading-5 text-red-600 hover:bg-red-50 focus:outline-none focus:bg-red-50 transition duration-150 ease-in-out">Déconnexion</button></div>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center space-x-2 pl-2"><a href="{{ route('login') }}" wire:navigate class="px-5 py-2.5 text-sm text-gray-700 hover:text-blue-600 font-medium transition-colors">Connexion</a><a href="{{ route('register') }}" wire:navigate class="px-5 py-2.5 text-sm font-medium bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full hover:shadow-lg hover:scale-105 transform transition-all duration-200">Inscription</a></div>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"><svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        {{-- Vous devrez ajouter les liens au menu mobile ici si vous voulez qu'ils apparaissent sur téléphone --}}
    </div>
</nav>
