<div class="bg-gray-50 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <!-- Titre de la page -->
        <div class="text-center mb-12">
            <h1 class="text-4xl lg:text-5xl font-extrabold text-gray-900 tracking-tight">
                Votre Panier
            </h1>
            <p class="mt-4 text-lg text-gray-600">Vérifiez vos articles et finalisez votre commande.</p>
        </div>

        @if(!empty($cartItems))
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 xl:gap-12 items-start">

                <!-- ========== COLONNE DE GAUCHE : LISTE DES ARTICLES ========== -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-4 sm:p-6 space-y-6">

                    @foreach($cartItems as $id => $item)
                        <!-- Un article du panier -->
                        <div wire:key="cart-item-{{ $id }}" class="flex items-center gap-4 sm:gap-6 border-b border-gray-100 pb-6 last:border-b-0 last:pb-0 transition-opacity" wire:loading.class.delay="opacity-50">
                            <!-- Image du produit -->
                            <a href="#" class="block w-24 h-24 sm:w-28 sm:h-28 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="{{ $item['image'] ? Storage::url($item['image']) : 'https://via.placeholder.com/150' }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                            </a>

                            <!-- Informations et actions -->
                            <div class="flex-grow flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex-grow">
                                    <h3 class="font-semibold text-gray-800 text-lg">{{ $item['name'] }}</h3>
                                    <p class="text-gray-500">{{ number_format($item['price'], 2, ',', ' ') }}€</p>
                                    <button wire:click="removeFromCart('{{ $id }}')" class="mt-2 text-sm text-red-500 hover:text-red-700 font-medium transition-colors flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        Retirer
                                    </button>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end gap-4">
                                    <!-- Sélecteur de quantité amélioré -->
                                    <div class="flex items-center border border-gray-200 rounded-full">
                                        <button wire:click="decrementQuantity('{{ $id }}')" wire:loading.attr="disabled" class="p-2 text-gray-500 hover:text-gray-800 transition-colors rounded-l-full hover:bg-gray-100 disabled:opacity-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" /></svg>
                                        </button>
                                        <span class="px-4 font-semibold text-gray-800" style="min-width: 40px; text-align: center;">{{ $item['quantity'] }}</span>
                                        <button wire:click="incrementQuantity('{{ $id }}')" wire:loading.attr="disabled" class="p-2 text-gray-500 hover:text-gray-800 transition-colors rounded-r-full hover:bg-gray-100 disabled:opacity-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </div>

                                    <!-- Prix total de l'article -->
                                    <p class="font-bold text-gray-900 w-24 text-right">{{ number_format($item['quantity'] * $item['price'], 2, ',', ' ') }}€</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-right mt-4">
                        <button wire:click="clearCart" class="text-sm font-medium text-gray-500 hover:text-red-600 transition-colors">
                            Vider le panier
                        </button>
                    </div>
                </div>

                <!-- ========== COLONNE DE DROITE : RÉSUMÉ DE COMMANDE (STICKY) ========== -->
                <div class="lg:col-span-1 h-fit sticky top-8">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 border-b pb-4 mb-4">Résumé de la commande</h2>

                        <div class="space-y-3 text-gray-700">
                            <div class="flex justify-between">
                                <span>Sous-total</span>
                                <span>{{ number_format($this->subtotal, 2, ',', ' ') }}€</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Livraison</span>
                                <span class="font-medium text-green-600">Gratuite</span>
                            </div>
                        </div>

                        <div class="flex justify-between font-bold text-xl mt-6 pt-4 border-t">
                            <span>Total</span>
                            <span>{{ number_format($this->total, 2, ',', ' ') }}€</span>
                        </div>

                        <a href="#" class="group mt-8 block w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold text-lg text-center py-3 px-6 rounded-full hover:shadow-2xl hover:scale-105 transform transition-all duration-300">
                            <span class="group-hover:tracking-wider transition-all">Passer la commande</span>
                        </a>
                    </div>
                </div>

            </div>
        @else
            <!-- ========== ÉTAT PANIER VIDE ========== -->
            <div class="text-center bg-white p-12 rounded-xl shadow-lg max-w-lg mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-20 w-20 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2 class="mt-6 text-2xl font-bold text-gray-800">Votre panier est tristement vide.</h2>
                <p class="mt-2 text-gray-500">Il semble que vous n'ayez encore rien ajouté. Explorez nos collections !</p>
                <a href="{{ route('home') }}" wire:navigate class="group mt-8 inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold py-3 px-8 rounded-full hover:shadow-lg hover:scale-105 transform transition-all duration-300">
                    Continuer mes achats
                </a>
            </div>
        @endif
    </div>
</div>
