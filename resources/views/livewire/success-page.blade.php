<section class="flex items-center font-poppins dark:bg-gray-800">
    <div class="justify-center flex-1 max-w-6xl px-4 py-4 mx-auto bg-white border rounded-md dark:border-gray-900 dark:bg-gray-900 md:py-10 md:px-10">
        <div>
            <h1 class="px-4 mb-8 text-2xl font-semibold tracking-wide text-gray-700 dark:text-gray-300">
                Merci. Votre commande a été reçue.
            </h1>

            <!-- Customer Information -->
            <div class="flex border-b border-gray-200 dark:border-gray-700 items-stretch justify-start w-full h-full px-4 mb-8 md:flex-row xl:flex-col md:space-x-6 lg:space-x-8 xl:space-x-0">
                <div class="flex items-start justify-start flex-shrink-0">
                    <div class="flex items-center justify-center w-full pb-6 space-x-4 md:justify-start">
                        <div class="flex flex-col items-start justify-start space-y-2">
                            <p class="text-lg font-semibold leading-4 text-left text-gray-800 dark:text-gray-400">
                                {{ $order->user->name }}
                            </p>
                            <p class="text-sm leading-4 text-gray-600 dark:text-gray-400">
                                {{ $order->shippingAddress->address_line1 }}, {{ $order->shippingAddress->city }}
                            </p>
                            <p class="text-sm leading-4 text-gray-600 dark:text-gray-400">
                                Code postal: {{ $order->shippingAddress->postal_code }}
                            </p>
                            <p class="text-sm leading-4 cursor-pointer dark:text-gray-400">
                                Téléphone: {{ $order->shippingAddress->phone }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="flex flex-wrap items-center pb-4 mb-10 border-b border-gray-200 dark:border-gray-700">
                <div class="w-full px-4 mb-4 md:w-1/4">
                    <p class="mb-2 text-sm leading-5 text-gray-600 dark:text-gray-400">
                        Numéro de commande:
                    </p>
                    <p class="text-base font-semibold leading-4 text-gray-800 dark:text-gray-400">
                        N°{{ $order->id }}
                    </p>
                </div>
                <div class="w-full px-4 mb-4 md:w-1/4">
                    <p class="mb-2 text-sm leading-5 text-gray-600 dark:text-gray-400">
                        Date:
                    </p>
                    <p class="text-base font-semibold leading-4 text-gray-800 dark:text-gray-400">
                        {{ $order->created_at->format('d-m-Y') }}
                    </p>
                </div>
                <div class="w-full px-4 mb-4 md:w-1/4">
                    <p class="mb-2 text-sm font-medium leading-5 text-gray-800 dark:text-gray-400">
                        Total:
                    </p>
                    <p class="text-base font-semibold leading-4 text-blue-600 dark:text-gray-400">
                        {{ number_format($order->total, 0, ',', ' ') }} FCFA
                    </p>
                </div>
                <div class="w-full px-4 mb-4 md:w-1/4">
                    <p class="mb-2 text-sm leading-5 text-gray-600 dark:text-gray-400">
                        Méthode de paiement:
                    </p>
                    <p class="text-base font-semibold leading-4 text-gray-800 dark:text-gray-400">
                        @if($order->payment_method === 'à_la_livraison')
                            Paiement à la livraison
                        @else
                            Paiement en ligne
                        @endif
                    </p>
                </div>
            </div>

            <!-- Order Items -->
            <div class="px-4 mb-6">
                <h2 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-400">Articles commandés</h2>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-200 dark:border-gray-700">
                        <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Produit</th>
                            <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Quantité</th>
                            <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-right text-sm font-medium text-gray-700 dark:text-gray-300">Prix unitaire</th>
                            <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-right text-sm font-medium text-gray-700 dark:text-gray-300">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td class="border border-gray-200 dark:border-gray-700 px-4 py-2">
                                    <div class="flex items-center space-x-3">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-12 h-12 object-cover rounded">
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 dark:text-gray-400">{{ $item->product->name }}</p>
                                            @if($item->product->description)
                                                <p class="text-xs text-gray-600 dark:text-gray-500">{{ Str::limit($item->product->description, 50) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-center text-sm text-gray-800 dark:text-gray-400">
                                    {{ $item->quantity }}
                                </td>
                                <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-right text-sm text-gray-800 dark:text-gray-400">
                                    {{ number_format($item->price, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-right text-sm font-medium text-gray-800 dark:text-gray-400">
                                    {{ number_format($item->total, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Details and Shipping -->
            <div class="px-4 mb-10">
                <div class="flex flex-col items-stretch justify-center w-full space-y-4 md:flex-row md:space-y-0 md:space-x-8">
                    <!-- Order Summary -->
                    <div class="flex flex-col w-full space-y-6">
                        <h2 class="mb-2 text-xl font-semibold text-gray-700 dark:text-gray-400">Détails de la commande</h2>
                        <div class="flex flex-col items-center justify-center w-full pb-4 space-y-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between w-full">
                                <p class="text-base leading-4 text-gray-800 dark:text-gray-400">Sous-total</p>
                                <p class="text-base leading-4 text-gray-600 dark:text-gray-400">
                                    {{ number_format($order->orderItems->sum('total'), 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                            <div class="flex items-center justify-between w-full">
                                <p class="text-base leading-4 text-gray-800 dark:text-gray-400">Réduction</p>
                                <p class="text-base leading-4 text-gray-600 dark:text-gray-400">0,00 FCFA</p>
                            </div>
                            <div class="flex items-center justify-between w-full">
                                <p class="text-base leading-4 text-gray-800 dark:text-gray-400">Livraison</p>
                                <p class="text-base leading-4 text-gray-600 dark:text-gray-400">Gratuite</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between w-full">
                            <p class="text-base font-semibold leading-4 text-gray-800 dark:text-gray-400">Total</p>
                            <p class="text-base font-semibold leading-4 text-gray-600 dark:text-gray-400">
                                {{ number_format($order->total, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="flex flex-col w-full px-2 space-y-4 md:px-8">
                        <h2 class="mb-2 text-xl font-semibold text-gray-700 dark:text-gray-400">Livraison</h2>
                        <div class="flex items-start justify-between w-full">
                            <div class="flex items-center justify-center space-x-2">
                                <div class="w-8 h-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-6 h-6 text-blue-600 dark:text-blue-400 bi bi-truck" viewBox="0 0 16 16">
                                        <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"></path>
                                    </svg>
                                </div>
                                <div class="flex flex-col items-center justify-start">
                                    <p class="text-lg font-semibold leading-6 text-gray-800 dark:text-gray-400">
                                        Livraison<br>
                                        <span class="text-sm font-normal">Livraison sous 24-48h</span>
                                    </p>
                                </div>
                            </div>
                            <p class="text-lg font-semibold leading-6 text-gray-800 dark:text-gray-400">Gratuite</p>
                        </div>

                        <!-- Order Status -->
                        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Statut de la commande</h3>
                            <div class="flex items-center space-x-2">
                                @php
                                    $statusColors = [
                                        'en_attente' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'en_traitement' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'expédiée' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                        'livrée' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'annulée' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                                    ];
                                    $paymentStatusColors = [
                                        'non_payé' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        'payé' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'remboursé' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $paymentStatusColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-start gap-4 px-4 mt-6">
                {{-- Bouton pour retourner à l'accueil --}}
                <a href="{{ route('home') }}" wire:navigate
                   class="w-full sm:w-auto text-center px-6 py-3 text-blue-600 border border-blue-500 rounded-lg font-semibold hover:text-white hover:bg-blue-600 transition-all duration-300 ease-in-out">
                    Continuer les achats
                </a>

                {{-- Bouton pour télécharger la facture --}}
                @auth
                    <a href="{{ route('invoice.download', ['order' => $order->id]) }}"
                       class="w-full sm:w-auto text-center px-6 py-3 bg-blue-500 rounded-lg text-white font-semibold hover:bg-blue-600 transition-all duration-300 ease-in-out inline-flex items-center justify-center gap-x-2 shadow-md hover:shadow-lg">

                        {{-- Icône SVG pour le téléchargement --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>

                        <span>Télécharger la Facture</span>
                    </a>
                @endauth
            </div>

            <!-- Success Message -->
            @if(session('success_message'))
                <div class="mt-6 px-4">
                    <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                    {{ session('success_message') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
