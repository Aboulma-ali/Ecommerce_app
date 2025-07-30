<div class="container mx-auto p-6">
    @if ($selectedOrder)
        <!-- Détails de la commande -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Détails de la Commande</h1>
                <button wire:click="showList" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                    &larr; Retour à mes commandes
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-md">
                    <h2 class="font-semibold text-gray-600">Numéro de commande</h2>
                    <p class="text-lg text-gray-900">{{ str_pad($selectedOrder->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-md">
                    <h2 class="font-semibold text-gray-600">Date</h2>
                    <p class="text-lg text-gray-900">{{ $selectedOrder->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-md">
                    <h2 class="font-semibold text-gray-600">Client</h2>
                    <p class="text-lg text-gray-900">
                        {{ $selectedOrder->user->name }}<br>
                        <span class="text-sm text-gray-600">{{ $selectedOrder->user->email }}</span>
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-md">
                    <h2 class="font-semibold text-gray-600">Statut de la commande</h2>
                    <span class="px-3 py-1 text-sm font-semibold text-white rounded-full
                        @switch($selectedOrder->status)
                            @case('en_attente') bg-yellow-500 @break
                            @case('en_traitement') bg-blue-500 @break
                            @case('expediee') bg-purple-500 @break
                            @case('livree') bg-green-500 @break
                            @case('annulee') bg-red-500 @break
                            @default bg-gray-500
                        @endswitch">
                        {{ ucfirst(str_replace('_', ' ', $selectedOrder->status)) }}
                    </span>
                </div>
                <div class="bg-gray-50 p-4 rounded-md">
                    <h2 class="font-semibold text-gray-600">Mode de paiement</h2>
                    <p class="text-lg text-gray-900">
                        @if($selectedOrder->payment_method === 'en_ligne')
                            <span class="text-blue-600">💳 Paiement en ligne</span>
                        @else
                            <span class="text-green-600">💵 Paiement à la livraison</span>
                        @endif
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-md">
                    <h2 class="font-semibold text-gray-600">Statut du paiement</h2>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full
                        @if($selectedOrder->payment_status === 'payé')
                            bg-green-100 text-green-800
                        @elseif($selectedOrder->payment_status === 'en_attente')
                            bg-yellow-100 text-yellow-800
                        @else
                            bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $selectedOrder->payment_status)) }}
                    </span>
                </div>
            </div>

            <!-- Adresse de livraison dans une section séparée -->
            <div class="bg-gray-50 p-4 rounded-md mb-6">
                <h2 class="font-semibold text-gray-600 mb-2">Adresse de livraison</h2>
                <p class="text-gray-900">
                    {{ $selectedOrder->shippingAddress->name }}<br>
                    {{ $selectedOrder->shippingAddress->address_line1 }}<br>
                    {{ $selectedOrder->shippingAddress->postal_code }} {{ $selectedOrder->shippingAddress->city }}<br>
                    📞 {{ $selectedOrder->shippingAddress->phone }}
                </p>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-4">Produits commandés</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border">
                    <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4 border-b text-left">Produit</th>
                        <th class="py-2 px-4 border-b text-center">Quantité</th>
                        <th class="py-2 px-4 border-b text-right">Prix unitaire</th>
                        <th class="py-2 px-4 border-b text-right">Sous-total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($selectedOrder->items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b">{{ $item->product->name }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $item->quantity }}</td>
                            <td class="py-2 px-4 border-b text-right">{{ number_format($item->price, 2, ',', ' ') }} €</td>
                            <td class="py-2 px-4 border-b text-right">{{ number_format($item->total, 2, ',', ' ') }} €</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                    <tr>
                        <td colspan="3" class="py-3 px-4 text-right">Total de la commande</td>
                        <td class="py-3 px-4 text-right text-xl">{{ number_format($selectedOrder->total, 2, ',', ' ') }} €</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @else
        <!-- Liste des commandes -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Mes Commandes</h1>
            <p class="text-gray-600 mb-6">Bienvenue {{ auth()->user()->name }}, voici l'historique de vos commandes</p>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border">
                    <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4 border-b text-left">#</th>
                        <th class="py-2 px-4 border-b text-left">Date</th>
                        <th class="py-2 px-4 border-b text-center">Statut</th>
                        <th class="py-2 px-4 border-b text-center">Mode de paiement</th>
                        <th class="py-2 px-4 border-b text-center">Statut paiement</th>
                        <th class="py-2 px-4 border-b text-right">Total</th>
                        <th class="py-2 px-4 border-b text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-2 px-4 border-b">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="py-2 px-4 border-b text-center">
                                <span class="px-2 py-1 text-xs font-semibold text-white rounded-full
                                    @switch($order->status)
                                        @case('en_attente') bg-yellow-500 @break
                                        @case('en_traitement') bg-blue-500 @break
                                        @case('expediee') bg-purple-500 @break
                                        @case('livree') bg-green-500 @break
                                        @case('annulee') bg-red-500 @break
                                        @default bg-gray-500
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border-b text-center">
                                @if($order->payment_method === 'en_ligne')
                                    <span class="text-blue-600">💳 En ligne</span>
                                @else
                                    <span class="text-green-600">💵 À la livraison</span>
                                @endif
                            </td>
                            <td class="py-2 px-4 border-b text-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($order->payment_status === 'payé')
                                        bg-green-100 text-green-800
                                    @elseif($order->payment_status === 'en_attente')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border-b text-right font-semibold">{{ number_format($order->total, 2, ',', ' ') }} €</td>
                            <td class="py-2 px-4 border-b text-center">
                                <a href="{{route('success.index')}}" class="px-3 py-1 bg-indigo-500 text-white text-sm rounded-md hover:bg-indigo-600 transition">
                                    Voir détails
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 px-4 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-lg">Vous n'avez pas encore passé de commande.</p>
                                    <a href="{{ route('home') }}" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                                        Commencer vos achats
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    @endif
</div>
