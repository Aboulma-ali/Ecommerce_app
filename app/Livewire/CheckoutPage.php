<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Livewire\Traits\CartActions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutPage extends Component
{
    use CartActions;

    public array $cartItems = [];
    public array $shippingAddress = [
        'name' => '',
        'address_line1' => '',
        'city' => '',
        'postal_code' => '',
        'phone' => '',
    ];
    public string $paymentMethod = 'en_ligne';
    public int $step = 1;

    public function mount()
    {
        if (!auth()->check()) {
            Log::info('User not authenticated, redirecting to login');
            return redirect()->route('login');
        }

        $this->cartItems = $this->getCartInstance()->toArray();
        Log::info('Cart items loaded in mount:', ['cartItems' => $this->cartItems]);

        if (empty($this->cartItems)) {
            Log::info('Cart is empty, redirecting to home');
            return redirect()->route('home');
        }

        $defaultAddress = auth()->user()->addresses()->where('type', 'livraison')->first();
        if ($defaultAddress) {
            $this->shippingAddress = $defaultAddress->only(['name', 'address_line1', 'city', 'postal_code', 'phone']);
        } else {
            $this->shippingAddress['name'] = auth()->user()->name;
        }
    }

    public function getSubtotalProperty()
    {
        return collect($this->cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function getShippingCostProperty()
    {
        return 0.00; // À ajuster si vous avez des frais de livraison dynamiques
    }

    public function getTotalProperty()
    {
        return $this->subtotal + $this->shippingCost;
    }

    public function getSecureTotalProperty()
    {
        return $this->calculateServerTotal();
    }

    private function calculateServerTotal(): float
    {
        $productIds = collect($this->cartItems)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $total = collect($this->cartItems)->reduce(function ($carry, $item) use ($products) {
            if (!isset($products[$item['product_id']])) {
                Log::error('Product not found in calculateServerTotal', ['product_id' => $item['product_id']]);
                throw new \Exception("Le produit avec l'ID {$item['product_id']} n'a pas été trouvé.");
            }
            $productPrice = $products[$item['product_id']]->price;
            return $carry + ($productPrice * $item['quantity']);
        }, 0);

        return $total + $this->shippingCost;
    }

    private function validateCartItems(): void
    {
        Log::info('Validating cart items', ['cartItems' => $this->cartItems]);
        $productIds = collect($this->cartItems)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

        foreach ($this->cartItems as $item) {
            if (!isset($products[$item['product_id']])) {
                Log::error('Product not found', ['product_id' => $item['product_id']]);
                throw new \Exception("Le produit avec l'ID {$item['product_id']} n'a pas été trouvé.");
            }
            $product = $products[$item['product_id']];
            if ($product->stock < $item['quantity']) {
                Log::error('Insufficient stock for product', ['product_id' => $item['product_id'], 'stock' => $product->stock, 'quantity' => $item['quantity']]);
                throw new \Exception("Stock insuffisant pour le produit {$product->name}.");
            }
        }
        Log::info('Cart items validated successfully');
    }

    public function nextStep()
    {
        $this->validate([
            'shippingAddress.name' => 'required|string|max:255',
            'shippingAddress.address_line1' => 'required|string|max:255',
            'shippingAddress.city' => 'required|string|max:255',
            'shippingAddress.postal_code' => 'required|string|max:10',
            'shippingAddress.phone' => 'required|string|max:20',
            'paymentMethod' => ['required', Rule::in(['en_ligne', 'à_la_livraison'])],
        ]);

        $this->step++;
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function createPaymentIntent()
    {
        if ($this->paymentMethod !== 'en_ligne') {
            Log::info('createPaymentIntent skipped: paymentMethod is not en_ligne', ['paymentMethod' => $this->paymentMethod]);
            return;
        }

        Log::info('createPaymentIntent started for user: ' . auth()->id());

        try {
            // Vérifier la clé Stripe
            if (empty(env('STRIPE_SECRET'))) {
                Log::error('STRIPE_SECRET is not set in .env');
                throw new \Exception('Clé Stripe non configurée.');
            }

            // Valider le panier
            Log::info('Validating cart items...');
            $this->validateCartItems();

            // Calculer le total sécurisé
            Log::info('Calculating server total...');
            $totalAmount = $this->calculateServerTotal();
            Log::info('Server total calculated: ' . $totalAmount);

            if ($totalAmount <= 0) {
                Log::error('Total amount is zero or negative', ['totalAmount' => $totalAmount]);
                throw new \Exception('Le montant total ne peut pas être zéro.');
            }

            Stripe::setApiKey(env('STRIPE_SECRET'));
            Log::info('Creating Stripe PaymentIntent...');
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($totalAmount * 100),
                'currency' => 'eur',
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => ['user_id' => auth()->id()],
            ]);

            Log::info('About to dispatch paymentIntentCreated event', ['client_secret' => $paymentIntent->client_secret]);
            $this->dispatch('paymentIntentCreated', $paymentIntent->client_secret);
            Log::info('paymentIntentCreated event dispatched successfully');

            return $paymentIntent->client_secret;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API error: ' . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('error', 'Erreur lors de la création du paiement : ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('General error in createPaymentIntent: ' . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('error', $e->getMessage());
            session()->flash('error', $e->getMessage());
            return redirect()->route('cart');
        }
    }

    public function placeOrder()
    {
        $this->validate([
            'shippingAddress.name' => 'required|string|max:255',
            'shippingAddress.address_line1' => 'required|string|max:255',
            'shippingAddress.city' => 'required|string|max:255',
            'shippingAddress.postal_code' => 'required|string|max:10',
            'shippingAddress.phone' => 'required|string|max:20',
            'paymentMethod' => ['required', Rule::in(['en_ligne', 'à_la_livraison'])],
        ]);

        if ($this->paymentMethod !== 'à_la_livraison') {
            return;
        }

        try {
            $order = DB::transaction(function () {
                // Valider le panier
                $this->validateCartItems();

                // Calculer le total sécurisé
                $total = $this->calculateServerTotal();
                $productIds = collect($this->cartItems)->pluck('product_id');
                $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                $address = Address::updateOrCreate(
                    ['user_id' => auth()->id(), 'type' => 'livraison'],
                    $this->shippingAddress
                );

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'shipping_address_id' => $address->id,
                    'total' => $total,
                    'status' => 'en_attente',
                    'payment_status' => 'non_payé',
                    'payment_method' => 'à_la_livraison',
                ]);

                foreach ($this->cartItems as $item) {
                    $product = $products[$item['product_id']];
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'total' => $product->price * $item['quantity'],
                    ]);
                    $product->decrement('stock', $item['quantity']);
                }

                return $order;
            });

            $this->clearCart();
            session()->flash('success_message', 'Votre commande #' . $order->id . ' a bien été passée ! Vous paierez à la livraison.');
            return redirect()->route('order.confirmation', ['order' => $order->id]);
        } catch (\Exception $e) {
            Log::error('Error in placeOrder: ' . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('error', 'Une erreur est survenue lors de la création de la commande : ' . $e->getMessage());
            session()->flash('error', $e->getMessage());
            return redirect()->route('cart');
        }
    }

    public function confirmOrder($paymentIntentId)
    {
        if ($this->paymentMethod !== 'en_ligne') {
            return;
        }

        try {
            // Vérifier le paiement auprès de Stripe
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status !== 'succeeded') {
                throw new \Exception('Le paiement n\'a pas abouti.');
            }

            if ($paymentIntent->metadata['user_id'] != auth()->id()) {
                throw new \Exception('Le Payment Intent ne correspond pas à l\'utilisateur connecté.');
            }

            $order = DB::transaction(function () use ($paymentIntent) {
                // Valider le panier
                $this->validateCartItems();

                // Calculer le total sécurisé
                $serverTotal = $this->calculateServerTotal();

                // Vérifier l'incohérence de montant
                if ((int) ($serverTotal * 100) !== $paymentIntent->amount) {
                    throw new \Exception('Incohérence de montant détectée. Tentative de fraude potentielle.');
                }

                $address = Address::updateOrCreate(
                    ['user_id' => auth()->id(), 'type' => 'livraison'],
                    $this->shippingAddress
                );

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'shipping_address_id' => $address->id,
                    'total' => $serverTotal,
                    'status' => 'en_traitement',
                    'payment_status' => 'payé',
                    'payment_method' => 'en_ligne',
                    'payment_intent_id' => $paymentIntent->id,
                ]);

                $productIds = collect($this->cartItems)->pluck('product_id');
                $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                foreach ($this->cartItems as $item) {
                    $product = $products[$item['product_id']];
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'total' => $product->price * $item['quantity'],
                    ]);
                    $product->decrement('stock', $item['quantity']);
                }

                return $order;
            });

            $this->clearCart();
            session()->flash('success_message', 'Paiement réussi ! Votre commande #' . $order->id . ' a bien été passée.');
            return redirect()->route('order.confirmation', ['order' => $order->id]);
        } catch (\Exception $e) {
            Log::error('Error in confirmOrder: ' . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('error', 'Une erreur critique est survenue : ' . $e->getMessage());
            session()->flash('error', $e->getMessage());
            return redirect()->route('cart');
        }
    }

    public function render()
    {
        try {
            $this->validateCartItems();
        } catch (\Exception $e) {
            Log::error('Error in render: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', $e->getMessage());
            return redirect()->route('/');
        }

        return view('livewire.checkout-page')->layout('layouts.app');
    }

    public function formatMoney(float $amount, string $currency = 'EUR'): string
    {
        return number_format($amount, 2, ',', ' ') . ' €';
    }
}
