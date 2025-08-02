<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <section class="py-10 bg-gray-50 font-poppins dark:bg-gray-800 rounded-lg">
        <div class="px-4 py-4 mx-auto max-w-7xl lg:py-6 md:px-6">
            <div class="flex flex-wrap mb-24 -mx-3">
                <!-- Sidebar des filtres -->
                <div class="w-full pr-2 lg:w-1/4 lg:block">
                    <!-- Filtrage par catégories -->
                    <div class="p-4 mb-5 bg-white border border-gray-200 dark:border-gray-900 dark:bg-gray-900">
                        <h2 class="text-2xl font-bold dark:text-gray-400">Categories</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                        <ul>
                            @foreach($categories as $category)
                                <li class="mb-4">
                                    <label for="category_{{ $category->id }}" class="flex items-center dark:text-gray-400 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            id="category_{{ $category->id }}"
                                            wire:model.live="selectedCategories"
                                            value="{{ $category->id }}"
                                            class="w-4 h-4 mr-2 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-lg">{{ $category->name }}</span>
                                        <span class="ml-2 text-sm text-gray-500">({{ $category->products->count() }})</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Bouton pour réinitialiser les filtres -->
                        @if(!empty($selectedCategories) || $inStock || $onSale || $priceRange != 500000)
                            <button
                                wire:click="resetFilters"
                                class="mt-4 w-full px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                Réinitialiser les filtres
                            </button>
                        @endif
                    </div>

                    <!-- Filtrage par statut du produit -->
                    <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                        <h2 class="text-2xl font-bold dark:text-gray-400">Product Status</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                        <ul>
                            <li class="mb-4">
                                <label for="in_stock" class="flex items-center dark:text-gray-300 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        id="in_stock"
                                        wire:model.live="inStock"
                                        class="w-4 h-4 mr-2 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="text-lg dark:text-gray-400">In Stock</span>
                                </label>
                            </li>
                        </ul>
                    </div>

                    <!-- Filtrage par prix -->
                    <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                        <h2 class="text-2xl font-bold dark:text-gray-400">Price</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                        <div>
                            <input
                                type="range"
                                wire:model.live="priceRange"
                                class="w-full h-1 mb-4 bg-blue-100 rounded appearance-none cursor-pointer"
                                max="500000"
                                min="1000"
                                step="10000">
                            <div class="flex justify-between">
                                <span class="inline-block text-lg font-bold text-blue-400">1,000 FCFA</span>
                                <span class="inline-block text-lg font-bold text-blue-400">{{ number_format($priceRange, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Zone d'affichage des produits -->
                <div class="w-full px-3 lg:w-3/4">
                    <!-- Barre de tri et informations -->
                    <div class="px-3 mb-4">
                        <div class="items-center justify-between flex px-3 py-2 bg-gray-100 dark:bg-gray-900 rounded-lg">
                            <div class="flex items-center justify-between">
                                <select
                                    wire:model.live="sortBy"
                                    class="block w-40 text-base bg-gray-100 cursor-pointer dark:text-gray-400 dark:bg-gray-900 border-0 focus:ring-0">
                                    <option value="latest">Sort by latest</option>
                                    <option value="price_asc">Price: Low to High</option>
                                    <option value="price_desc">Price: High to Low</option>
                                    <option value="name">Name A-Z</option>
                                </select>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ count($products) }} produit(s) trouvé(s)
                            </div>
                        </div>
                    </div>

                    <!-- Filtres actifs -->
                    @if(!empty($selectedCategories) || $inStock || $onSale || $priceRange != 500000)
                        <div class="mb-4 px-3">
                            <div class="flex flex-wrap gap-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400 mr-2">Filtres actifs:</span>

                                @foreach($selectedCategories as $categoryId)
                                    @php
                                        $category = $categories->find($categoryId);
                                    @endphp
                                    @if($category)
                                        <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                            {{ $category->name }}
                                            <button wire:click="$set('selectedCategories', {{ json_encode(array_diff($selectedCategories, [$categoryId])) }})" class="ml-1 text-blue-600 hover:text-blue-800">
                                                ×
                                            </button>
                                        </span>
                                    @endif
                                @endforeach

                                @if($inStock)
                                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                        En stock
                                        <button wire:click="$set('inStock', false)" class="ml-1 text-green-600 hover:text-green-800">×</button>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Indicateur de chargement -->
                    <div wire:loading class="flex justify-center items-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-gray-600 dark:text-gray-400">Filtrage en cours...</span>
                    </div>

                    <!-- Section d'affichage des produits -->
                    <div class="w-full" wire:loading.remove>
                        @if($products && $products->isNotEmpty())
                            <!-- Grille responsive pour les produits -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                @foreach($products as $product)
                                    <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700">
                                        <!-- Container Image -->
                                        <div class="relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                                            <a href="/product/{{ $product->id }}" wire:navigate class="block">
                                                <img
                                                    src="{{ url('storage', $product->image) }}"
                                                    alt="{{ $product->name }}"
                                                    class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500"
                                                    loading="lazy">
                                            </a>

                                            <!-- Badge de réduction -->
                                            @if(isset($product->discount) && $product->discount > 0)
                                                <div class="absolute top-3 left-3 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold shadow-md">
                                                    -{{ $product->discount }}%
                                                </div>
                                            @endif
                                            <!-- Badge de stock -->
                                            @if(isset($product->stock) && $product->stock <= 5 && $product->stock > 0)
                                                <div class="absolute bottom-3 left-3 bg-orange-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                    Plus que {{ $product->stock }} en stock
                                                </div>
                                            @elseif(isset($product->stock) && $product->stock <= 0)
                                                <div class="absolute bottom-3 left-3 bg-gray-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                    Rupture de stock
                                                </div>
                                            @endif
                                            <livewire:add-to-cart-button :product="$product" :key="'add-to-cart-'.$product->id" />
                                        </div>

                                        <!-- Contenu du produit -->
                                        <div class="p-5">
                                            <!-- Nom du produit -->
                                            <a href="/product/{{ $product->id }}" wire:navigate>
                                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-lg mb-2 line-clamp-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors leading-tight">
                                                    {{ $product->name }}
                                                </h3>
                                            </a>

                                            <!-- Catégorie -->
                                            @if(isset($product->category))
                                                <p class="text-xs text-blue-600 dark:text-blue-400 font-medium mb-2 uppercase tracking-wide">
                                                    {{ $product->category->name ?? $product->category }}
                                                </p>
                                            @endif

                                            <!-- Section Prix -->
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="flex flex-col">
                                                    @if(isset($product->original_price) && $product->original_price > $product->price)
                                                        <span class="text-xl font-bold text-green-600 dark:text-green-400">
                                                            {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                                        </span>
                                                        <span class="text-sm text-gray-500 line-through">
                                                            {{ number_format($product->original_price, 0, ',', ' ') }} FCFA
                                                        </span>
                                                    @else
                                                        <span class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                                            {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- État vide amélioré -->
                            <div class="text-center py-16">
                                <div class="mx-auto w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full flex items-center justify-center mb-6 shadow-inner">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-3">Aucun produit trouvé</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto leading-relaxed">
                                    Aucun produit ne correspond aux critères sélectionnés. Essayez de modifier vos filtres.
                                </p>
                                <button
                                    wire:click="resetFilters"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Réinitialiser les filtres
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
