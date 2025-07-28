<div class="bg-white font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

            <!-- ========== COLONNE DE GAUCHE : GALERIE D'IMAGES ========== -->
            <div class="sticky top-8"
                 x-data="{ show: true }"
                 @image-selected.window="show = false; setTimeout(() => show = true, 50)">
                <!-- Image principale avec transition -->
                <div class="aspect-square w-full bg-gray-100 rounded-2xl shadow-lg overflow-hidden">
                    <img :src="$wire.activeImageUrl"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover transition-opacity duration-300"
                         x-show="show"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                </div>

                <!-- Miniatures -->
                @php
                    // On combine l'image principale et les images de la galerie pour les miniatures
                    $galleryImages = collect();
                    if ($product->image) {
                        $galleryImages->push(['path' => $product->image]);
                    }
                    foreach ($product->images as $img) {
                        $galleryImages->push(['path' => $img->image_path]);
                    }
                @endphp

                @if($galleryImages->count() > 1)
                    <div class="mt-4 grid grid-cols-5 gap-4">
                        @foreach($galleryImages as $image)
                            <div wire:click="selectImage('{{ $image['path'] }}')"
                                 class="aspect-square bg-gray-100 rounded-lg overflow-hidden cursor-pointer border-2 transition hover:border-blue-500 hover:opacity-80
                                 {{ $activeImageUrl == \Storage::url($image['path']) ? 'border-blue-500' : 'border-transparent' }}">
                                <img src="{{ \Storage::url($image['path']) }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- ========== COLONNE DE DROITE : INFORMATIONS ET ACTIONS (ne change presque pas) ========== -->
            <div>
                @if($product->category)
                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">{{ $product->category->name }}</a>
                @endif

                <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-900 tracking-tight mt-2 mb-4">{{ $product->name }}</h1>

                <div class="mb-6">
                    <span class="text-4xl font-bold text-blue-600">{{ number_format($product->price, 2, ',', ' ') }}€</span>
                </div>

                <div class="prose prose-lg text-gray-600 mb-8">
                    {!! $product->description !!}
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <button wire:click="addToCartHandler" wire:loading.attr="disabled" class="group flex-grow w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold text-lg text-center py-3 px-8 rounded-full hover:shadow-2xl hover:scale-105 transform transition-all duration-300 disabled:opacity-75 disabled:cursor-wait">
                        <span wire:loading.remove wire:target="addToCartHandler">Ajouter au panier</span>
                        <span wire:loading wire:target="addToCartHandler">Ajout en cours...</span>
                    </button>
                </div>
            </div>
        </div>

        @if($similarProducts->isNotEmpty())
            <div class="mt-24">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 mb-8">Vous pourriez aussi aimer</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($similarProducts as $similarProduct)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden group transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <div class="relative">
                                {{-- J'ai aussi corrigé le lien pour qu'il utilise l'ID du produit comme on l'a défini --}}
                                <a href="{{ route('product.show', $similarProduct) }}" wire:navigate class="block h-64">
                                    <img src="{{ $similarProduct->image ? Storage::url($similarProduct->image) : '...' }}"
                                         alt="{{ $similarProduct->name }}" class="w-full h-full object-cover">
                                </a>
                                <livewire:add-to-cart-button :product="$similarProduct" :key="'similar-'.$similarProduct->id" />
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 truncate">{{ $similarProduct->name }}</h3>
                                <p class="text-lg font-bold text-blue-600">{{ number_format($similarProduct->price, 2, ',', ' ') }}€</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif {{-- <--- LA CORRECTION EST ICI --}}

    </div>
</div>
