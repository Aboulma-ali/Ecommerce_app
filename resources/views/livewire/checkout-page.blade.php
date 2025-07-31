<div class="bg-gray-50 font-sans">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Titre -->
        <div class="text-center mb-12">
            <h1 class="text-4xl lg:text-5xl font-extrabold text-gray-900 tracking-tight">Finaliser ma commande</h1>
        </div>

        <!-- Étapes -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center {{ $step >= 1 ? 'text-blue-600' : 'text-gray-400' }}">
                    <div class="rounded-full h-8 w-8 flex items-center justify-center border-2 {{ $step >= 1 ? 'border-blue-600' : 'border-gray-400' }} font-bold">1</div>
                    <span class="ml-2 font-semibold">Adresse</span>
                </div>
                <div class="flex-auto border-t-2 mx-4 {{ $step >= 2 ? 'border-blue-600' : 'border-gray-300' }}"></div>
                <div class="flex items-center {{ $step >= 2 ? 'text-blue-600' : 'text-gray-400' }}">
                    <div class="rounded-full h-8 w-8 flex items-center justify-center border-2 {{ $step >= 2 ? 'border-blue-600' : 'border-gray-400' }} font-bold">2</div>
                    <span class="ml-2 font-semibold">Paiement</span>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            <!-- Étape 1 : Adresse -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                @if ($step == 1)
                    <div wire:key="step-1">
                        <h2 class="text-xl font-bold mb-4">Adresse de livraison</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
                                <input type="text" id="name" wire:model.defer="shippingAddress.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('shippingAddress.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="address1" class="block text-sm font-medium text-gray-700">Adresse</label>
                                <input type="text" id="address1" wire:model.defer="shippingAddress.address_line1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('shippingAddress.address_line1') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
                                    <input type="text" id="city" wire:model.defer="shippingAddress.city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('shippingAddress.city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700">Code postal</label>
                                    <input type="text" id="postal_code" wire:model.defer="shippingAddress.postal_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('shippingAddress.postal_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                                <input type="tel" id="phone" wire:model.defer="shippingAddress.phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('shippingAddress.phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="paymentMethod" class="block text-sm font-medium text-gray-700">Méthode de paiement</label>
                                <div class="space-y-4 mt-2">
                                    <label class="flex items-center p-4 border rounded-lg cursor-pointer transition {{ $paymentMethod == 'en_ligne' ? 'border-blue-600 ring-1 ring-blue-600' : 'border-gray-200' }}">
                                        <input type="radio" name="paymentMethod" wire:model.live="paymentMethod" value="en_ligne" class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                                        <div class="ml-4"><p class="font-semibold">Paiement en ligne</p><p class="text-sm text-gray-500">Carte bancaire (Stripe)</p></div>
                                    </label>
                                    <label class="flex items-center p-4 border rounded-lg cursor-pointer transition {{ $paymentMethod == 'à_la_livraison' ? 'border-blue-600 ring-1 ring-blue-600' : 'border-gray-200' }}">
                                        <input type="radio" name="paymentMethod" wire:model.live="paymentMethod" value="à_la_livraison" class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                                        <div class="ml-4"><p class="font-semibold">Paiement à la livraison</p><p class="text-sm text-gray-500">Payez en espèces à la réception.</p></div>
                                    </label>
                                </div>
                                @error('paymentMethod') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <button wire:click="nextStep" class="mt-6 w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700">
                            Continuer vers le paiement
                        </button>
                    </div>
                @endif

                <!-- Étape 2 : Paiement -->
                @if ($step == 2)
                    <div wire:key="step-2">
                        @if ($paymentMethod === 'en_ligne')
                            <div x-data="{
                                stripe: null,
                                elements: null,
                                cardElement: null,
                                errorMessage: '',
                                isLoading: false,
                                hasInitialized: false,

                                async init() {
                                    if (this.hasInitialized) {
                                        console.log('Stripe already initialized, skipping...');
                                        return;
                                    }
                                    this.hasInitialized = true;

                                    console.log('Alpine/Stripe Initializing...');

                                    // Vérifier si Stripe est chargé
                                    if (typeof Stripe === 'undefined') {
                                        console.error('Stripe.js not loaded. Ensure script tag for https://js.stripe.com/v3/ is included.');
                                        this.errorMessage = 'Erreur de chargement du module de paiement. Veuillez recharger la page.';
                                        return;
                                    }

                                    // Vérifier la clé publique
                                    const stripeKey = '{{ config('services.stripe.key') }}';
                                    if (!stripeKey) {
                                        console.error('Stripe public key is missing!');
                                        this.errorMessage = 'La configuration du paiement est incorrecte.';
                                        return;
                                    }

                                    try {
                                        this.stripe = Stripe(stripeKey);
                                        console.log('Stripe initialized successfully');

                                        // Attendre un peu pour que le DOM soit prêt
                                        await new Promise(resolve => setTimeout(resolve, 500));

                                        console.log('=== REQUESTING PAYMENT INTENT ===');

                                        // Approche alternative: utiliser $wire.call() au lieu de $wire.createPaymentIntent()
                                        try {
                                            console.log('Calling createPaymentIntent via $wire.call...');
                                            const result = await $wire.call('createPaymentIntent');
                                            console.log('createPaymentIntent call result:', result);

                                            // Si la méthode retourne directement le client_secret
                                            if (typeof result === 'string' && result.includes('_secret_')) {
                                                console.log('Got client_secret directly from method call:', result);
                                                this.setupStripeElements(result);
                                                return;
                                            }
                                        } catch (callError) {
                                            console.error('Error with $wire.call:', callError);
                                        }

                                        // Si $wire.call() ne fonctionne pas, utiliser l'approche événementielle
                                        console.log('Fallback to event-based approach...');

                                        // Enregistrer les listeners d'événements
                                        $wire.on('paymentIntentCreated', (data) => {
                                            console.log('=== PAYMENT INTENT EVENT RECEIVED ===');
                                            console.log('Raw data received:', data);
                                            console.log('Data type:', typeof data);

                                            let clientSecret;
                                            if (Array.isArray(data) && data.length > 0) {
                                                clientSecret = data[0];
                                            } else if (typeof data === 'string') {
                                                clientSecret = data;
                                            } else {
                                                clientSecret = data;
                                            }

                                            console.log('Extracted clientSecret:', clientSecret);

                                            if (typeof clientSecret !== 'string' || !clientSecret.includes('_secret_')) {
                                                console.error('Invalid Client Secret received:', clientSecret);
                                                this.errorMessage = 'Erreur d initialisation du paiement. Client secret invalide: ' + JSON.stringify(clientSecret);
                                                return;
                                            }

                                            console.log('Client Secret is valid, setting up Stripe Elements...');
                                            this.setupStripeElements(clientSecret);
                                        });

                                        $wire.on('error', (data) => {
                                            const message = Array.isArray(data) ? data[0] : data;
                                            console.log('Backend error received:', message);
                                            this.errorMessage = message;
                                            this.isLoading = false;
                                        });

                                        // Faire l'appel standard
                                        console.log('Calling $wire.createPaymentIntent()...');
                                        $wire.createPaymentIntent();

                                    } catch (error) {
                                        console.error('Error initializing Stripe:', error);
                                        this.errorMessage = 'Erreur lors de l initialisation du paiement : ' + error.message;
                                    }
                                },

                                setupStripeElements(clientSecret) {
                                    console.log('Setting up Stripe Elements...');

                                    // Attendre que l'élément soit disponible
                                    const checkElement = () => {
                                        const element = document.getElementById('stripe-form');
                                        if (!element) {
                                            console.log('Stripe form element not found, retrying...');
                                            setTimeout(checkElement, 100);
                                            return;
                                        }

                                        try {
                                            console.log('Creating Stripe Elements...');
                                            this.elements = this.stripe.elements({
                                                clientSecret: clientSecret,
                                                appearance: {
                                                    theme: 'stripe',
                                                    variables: {
                                                        colorPrimary: '#2563eb',
                                                    }
                                                }
                                            });

                                            this.cardElement = this.elements.create('payment', {
                                                layout: 'tabs'
                                            });

                                            this.cardElement.mount('#stripe-form');
                                            console.log('Stripe Elements mounted successfully.');

                                            // Effacer le message d'erreur si tout va bien
                                            this.errorMessage = '';

                                        } catch (error) {
                                            console.error('Error mounting Stripe Elements:', error);
                                            this.errorMessage = 'Erreur lors du montage du formulaire de paiement : ' + error.message;
                                        }
                                    };

                                    checkElement();
                                },

                                async handleSubmit() {
                                    if (!this.elements) {
                                        this.errorMessage = 'Le formulaire de paiement n est pas encore prêt.';
                                        return;
                                    }

                                    this.isLoading = true;
                                    this.errorMessage = '';

                                    console.log('Submitting payment...');
                                    try {
                                        const { error, paymentIntent } = await this.stripe.confirmPayment({
                                            elements: this.elements,
                                            redirect: 'if_required'
                                        });

                                        if (error) {
                                            console.error('Stripe payment error:', error.message);
                                            this.errorMessage = error.message;
                                            this.isLoading = false;
                                        } else {
                                            console.log('PaymentIntent confirmed:', paymentIntent.id);
                                            $wire.confirmOrder(paymentIntent.id);
                                        }
                                    } catch (error) {
                                        console.error('Error during payment confirmation:', error);
                                        this.errorMessage = 'Erreur lors de la confirmation du paiement : ' + error.message;
                                        this.isLoading = false;
                                    }
                                }
                            }" x-init="init()">
                                <h3 class="text-lg font-semibold mb-4">Informations de paiement</h3>

                                <!-- Message d'attente -->
                                <div x-show="!elements && !errorMessage" class="p-4 border rounded-lg bg-blue-50 text-blue-700 mb-4">
                                    <div class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Chargement du formulaire de paiement...
                                    </div>
                                </div>

                                <!-- Formulaire Stripe -->
                                <div id="stripe-form" class="p-4 border rounded-lg bg-white min-h-[200px]" x-show="!errorMessage"></div>

                                <!-- Messages d'erreur -->
                                <div x-show="errorMessage" x-text="errorMessage" class="p-4 border border-red-300 rounded-lg bg-red-50 text-red-700 text-sm mt-2"></div>

                                <div class="flex items-center justify-between mt-6">
                                    <button wire:click="previousStep" class="text-gray-600 font-bold py-3 px-4 rounded-lg hover:bg-gray-100">&larr; Revenir</button>
                                    <button @click="handleSubmit()" :disabled="isLoading" class="bg-green-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-700 disabled:opacity-50">
                                        <span x-show="!isLoading">Payer {{ number_format($this->secureTotal, 0, ',', ' ') }}FCFA</span>
                                        <span x-show="isLoading">Paiement en cours...</span>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="p-4 border rounded-lg bg-green-50 text-green-700">
                                <p>Vous avez choisi de payer à la livraison. Cliquez sur "Valider ma commande" pour finaliser.</p>
                            </div>
                            <div class="flex items-center justify-between mt-6">
                                <button wire:click="previousStep" class="text-gray-600 font-bold py-3 px-4 rounded-lg hover:bg-gray-100">&larr; Revenir</button>
                                <button wire:click="placeOrder" wire:loading.attr="disabled" class="bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 disabled:opacity-50">
                                    <span wire:loading.remove wire:target="placeOrder">Valider ma commande</span>
                                    <span wire:loading wire:target="placeOrder">Validation...</span>
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Résumé de la commande -->
            <div class="bg-white p-6 rounded-xl shadow-lg h-fit sticky top-8">
                <h2 class="text-xl font-bold text-gray-900 border-b pb-4 mb-4">Résumé de votre commande</h2>
                <div class="space-y-4">
                    @foreach ($cartItems as $item)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="{{ $item['image'] ? Storage::url($item['image']) : 'https://via.placeholder.com/50' }}" class="w-12 h-12 rounded object-cover mr-4">
                                <div>
                                    <p class="font-semibold">{{ $item['name'] }}</p>
                                    <p class="text-sm text-gray-500">Qté: {{ $item['quantity'] }}</p>
                                </div>
                            </div>
                            <p class="font-semibold">{{ number_format($item['quantity'] * $item['price'], 0, ',', ' ') }}FCFA</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 pt-4 border-t space-y-2">
                    <div class="flex justify-between text-gray-700">
                        <span>Sous-total</span>
                        <span>{{ number_format($this->subtotal, 0, ',', ' ') }}FCFA</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Livraison</span>
                        <span class="font-medium text-green-600">Gratuite</span>
                    </div>
                    <div class="flex justify-between font-bold text-xl mt-2 pt-2 border-t">
                        <span>Total</span>
                        <span>{{ number_format($this->secureTotal, 0, ',', ' ') }}FCFA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
