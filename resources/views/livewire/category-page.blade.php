<div>
    <!-- BÂNNIERE DE CATÉGORIE & FIL D'ARIANE -->
    <div class="relative bg-gray-800 py-20 sm:py-28">
        <img src="{{ $category->image_url ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1200' }}"
             alt="Bannière de la catégorie {{ $category->name }}"
             class="absolute inset-0 w-full h-full object-cover opacity-30">

        <div class="relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <nav class="text-sm mb-4">
                <a href="{{ route('home') }}" class="hover:underline opacity-80">Accueil</a>
                <span class="mx-2 opacity-50">/</span>
                <span class="font-semibold">{{ $category->name }}</span>
            </nav>
            <h1 class="text-4xl lg:text-6xl font-extrabold tracking-tight">{{ $category->name }}</h1>
            <p class="mt-4 text-lg max-w-2xl mx-auto opacity-90">{{ $category->description ?? 'Découvrez notre sélection des meilleurs produits.' }}</p>
        </div>
    </div>

    <!-- CONTENU PRINCIPAL : FILTRES + PRODUITS -->
    <div class="bg-white">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <!-- Colonne de Filtres (Gauche) -->
                <aside class="hidden lg:block">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Filtres</h2>
                    <div class="space-y-8">

                        <!-- Filtre par Prix (AUCUNE MARQUE ICI) -->
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-4">Prix</h3>
                            <input type="range"
                                   wire:model.live.debounce.150ms="priceRange"
                                   min="0"
                                   max="{{ $maxPrice }}"
                                   step="10"
                                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                            <div class="flex justify-between text-sm text-gray-600 mt-2">
                                <span>0€</span>
                                <span class="font-bold text-blue-600">Jusqu'à {{ number_format($priceRange, 0, ',', ' ') }}€</span>
                            </div>
                        </div>

                    </div>
                </aside>

                <!-- Grille de Produits (Droite) -->
                <main class="lg:col-span-3">
                    <!-- Header de la grille -->
                    <div class="flex items-center justify-between pb-6 border-b border-gray-200 mb-8">
                        <div class="text-gray-600"><span class="font-semibold text-gray-900">{{ $products->total() }}</span> produits</div>
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">Trier <svg class="w-5 h-5 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                            <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-2xl z-20">
                                <a href="#" wire:click.prevent="setSort('created_at')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Nouveautés</a>
                                <a href="#" wire:click.prevent="setSort('price')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Prix</a>
                                <a href="#" wire:click.prevent="setSort('name')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Nom : A-Z</a>
                            </div>
                        </div>
                    </div>

                    <!-- Indicateur de chargement -->
                    <div wire:loading.delay class="text-center w-full py-8"><span class="text-blue-600 font-semibold">Mise à jour des produits...</span></div>

                    <!-- Grille -->
                    <div wire:loading.remove>
                        @if($products->isNotEmpty())
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                                @foreach($products as $product)
                                    <!-- Carte Produit (AUCUNE MARQUE ICI) -->
                                    <div class="bg-white rounded-xl shadow-md overflow-hidden group transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                                        <div class="relative"><a href="#"><img src="{{ Storage::url($product->image) ?? 'https://via.placeholder.com/400' }}" alt="{{ $product->name }}" class="w-full h-64 object-cover"></a><button class="absolute bottom-4 right-4 w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 hover:bg-blue-700"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></button></div>
                                        <div class="p-6"><span class="text-sm text-gray-500">{{ $product->category->name }}</span><h3 class="font-semibold text-lg text-gray-800 mt-1 mb-2 truncate">{{ $product->name }}</h3><div class="flex items-baseline gap-2"><span class="text-2xl font-bold text-blue-600">{{ number_format($product->price, 2, ',', ' ') }}€</span></div></div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-16">{{ $products->links() }}</div>
                        @else
                            <div class="text-center py-16"><h3 class="text-xl font-semibold text-gray-800">Aucun produit ne correspond à votre recherche</h3><p class="text-gray-500 mt-2">Essayez d'augmenter la tranche de prix.</p></div>
                        @endif
                    </div>
                </main>
            </div>
        </div>
    </div>
</div>
