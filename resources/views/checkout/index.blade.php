@extends('layouts.app')

@section('title', 'Paiement')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Formulaire -->
        <div class="lg:flex-1">
            <form action="{{ route('checkout.process') }}" method="POST" id="payment-form">
                @csrf
                
                <!-- Étapes -->
                <div class="flex items-center mb-8">
                    <div class="flex items-center">
                        <span class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center">1</span>
                        <span class="ml-2 font-medium">Livraison</span>
                    </div>
                    <div class="flex-1 h-px bg-gray-300 mx-4"></div>
                    <div class="flex items-center">
                        <span class="w-8 h-8 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center">2</span>
                        <span class="ml-2 text-gray-500">Paiement</span>
                    </div>
                </div>

                <!-- Adresse de livraison -->
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h2 class="text-lg font-medium mb-6">Adresse de livraison</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Prénom -->
                        <div>
                            <label for="firstname" class="block text-sm font-medium text-gray-700 mb-2">
                                Prénom
                            </label>
                            <input type="text" name="firstname" id="firstname" required
                                   value="{{ auth()->user()->firstname ?? old('firstname') }}"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                        </div>

                        <!-- Nom -->
                        <div>
                            <label for="lastname" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom
                            </label>
                            <input type="text" name="lastname" id="lastname" required
                                   value="{{ auth()->user()->lastname ?? old('lastname') }}"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" id="email" required
                                   value="{{ auth()->user()->email ?? old('email') }}"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Téléphone
                            </label>
                            <input type="tel" name="phone" id="phone" required
                                   value="{{ auth()->user()->phone ?? old('phone') }}"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                        </div>

                        <!-- Adresse -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Adresse
                            </label>
                            <input type="text" name="address" id="address" required
                                   value="{{ auth()->user()->address ?? old('address') }}"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                        </div>

                        <!-- Complément d'adresse -->
                        <div class="md:col-span-2">
                            <label for="address_complement" class="block text-sm font-medium text-gray-700 mb-2">
                                Complément d'adresse (optionnel)
                            </label>
                            <input type="text" name="address_complement" id="address_complement"
                                   value="{{ auth()->user()->address_complement ?? old('address_complement') }}"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                        </div>

                        <!-- Code postal -->
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Code postal
                            </label>
                            <input type="text" name="postal_code" id="postal_code" required
                                   value="{{ auth()->user()->postal_code ?? old('postal_code') }}"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                        </div>

                        <!-- Ville -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                Ville
                            </label>
                            <input type="text" name="city" id="city" required
                                   value="{{ auth()->user()->city ?? old('city') }}"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                        </div>

                        <!-- Pays -->
                        <div class="md:col-span-2">
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                Pays
                            </label>
                            <select name="country" id="country" required
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                                <option value="FR" {{ (auth()->user()->country ?? old('country')) == 'FR' ? 'selected' : '' }}>
                                    France
                                </option>
                                <option value="BE" {{ (auth()->user()->country ?? old('country')) == 'BE' ? 'selected' : '' }}>
                                    Belgique
                                </option>
                                <option value="CH" {{ (auth()->user()->country ?? old('country')) == 'CH' ? 'selected' : '' }}>
                                    Suisse
                                </option>
                                <option value="LU" {{ (auth()->user()->country ?? old('country')) == 'LU' ? 'selected' : '' }}>
                                    Luxembourg
                                </option>
                            </select>
                        </div>
                    </div>

                    @auth
                        <div class="mt-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="save_address" value="1" checked
                                       class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                <span class="ml-2 text-sm text-gray-600">
                                    Sauvegarder cette adresse pour mes prochaines commandes
                                </span>
                            </label>
                        </div>
                    @endauth
                </div>

                <!-- Mode de livraison -->
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h2 class="text-lg font-medium mb-6">Mode de livraison</h2>

                    <div class="space-y-4">
                        @foreach($shipping_methods as $method)
                            <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="shipping_method" value="{{ $method->id }}" required
                                       {{ $loop->first ? 'checked' : '' }}
                                       class="mt-1 rounded-full border-gray-300 text-gray-900 focus:ring-gray-900">
                                <div class="ml-3">
                                    <div class="font-medium">{{ $method->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $method->description }}</div>
                                    <div class="text-sm font-medium mt-1">
                                        {{ $method->price > 0 ? $method->price_formatted : 'Gratuit' }}
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Mode de paiement -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-medium mb-6">Mode de paiement</h2>

                    <div class="space-y-4">
                        <!-- Carte bancaire -->
                        <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="card" required checked
                                   class="mt-1 rounded-full border-gray-300 text-gray-900 focus:ring-gray-900">
                            <div class="ml-3 flex-1">
                                <div class="flex justify-between">
                                    <div>
                                        <div class="font-medium">Carte bancaire</div>
                                        <div class="text-sm text-gray-500">Visa, Mastercard, CB</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <img src="/images/payments/visa.svg" alt="Visa" class="h-8">
                                        <img src="/images/payments/mastercard.svg" alt="Mastercard" class="h-8">
                                        <img src="/images/payments/cb.svg" alt="CB" class="h-8">
                                    </div>
                                </div>
                                
                                <div class="mt-4" id="card-element">
                                    <!-- Stripe Elements -->
                                </div>
                            </div>
                        </label>

                        <!-- PayPal -->
                        <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="paypal" required
                                   class="mt-1 rounded-full border-gray-300 text-gray-900 focus:ring-gray-900">
                            <div class="ml-3 flex-1">
                                <div class="flex justify-between">
                                    <div>
                                        <div class="font-medium">PayPal</div>
                                        <div class="text-sm text-gray-500">
                                            Paiement sécurisé avec votre compte PayPal
                                        </div>
                                    </div>
                                    <img src="/images/payments/paypal.svg" alt="PayPal" class="h-8">
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </form>
        </div>

        <!-- Résumé -->
        <div class="lg:w-96">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-medium mb-4">Résumé de la commande</h2>

                <!-- Articles -->
                <div class="divide-y">
                    @foreach($cart->items() as $item)
                        <div class="py-4 flex gap-4">
                            <img src="{{ $item->product->image_url }}" 
                                 alt="{{ $item->product->name }}"
                                 class="w-16 h-16 object-cover rounded-lg">
                            <div class="flex-1">
                                <h3 class="font-medium">{{ $item->product->name }}</h3>
                                <div class="text-sm text-gray-500">
                                    <span>Taille: {{ $item->size->name }}</span>
                                    <span class="mx-1">•</span>
                                    <span>Couleur: {{ $item->color->name }}</span>
                                </div>
                                <div class="mt-1">
                                    <span class="font-medium">{{ $item->price_formatted }}</span>
                                    <span class="text-gray-500">× {{ $item->quantity }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Totaux -->
                <div class="border-t pt-4 mt-4 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sous-total</span>
                        <span>{{ $cart->subtotal_formatted }}</span>
                    </div>

                    @if($cart->coupon)
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-gray-600">Réduction</span>
                                <p class="text-sm text-gray-500">{{ $cart->coupon->code }}</p>
                            </div>
                            <span class="text-green-600">-{{ $cart->discount_formatted }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-gray-600">Livraison</span>
                        <span>{{ $cart->shipping_formatted }}</span>
                    </div>

                    @if($cart->tax > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">TVA</span>
                            <span>{{ $cart->tax_formatted }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between pt-4 border-t font-medium text-lg">
                        <span>Total</span>
                        <span>{{ $cart->total_formatted }}</span>
                    </div>
                </div>

                <!-- Bouton de paiement -->
                <div class="mt-6">
                    <button type="submit" form="payment-form"
                            class="w-full bg-gray-900 text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-colors">
                        Payer {{ $cart->total_formatted }}
                    </button>
                </div>

                <div class="mt-4 text-sm text-gray-500">
                    <p class="flex items-center justify-center mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Paiement 100% sécurisé
                    </p>
                    <p class="text-center">
                        En passant commande, vous acceptez nos 
                        <a href="{{ route('terms') }}" class="underline hover:text-gray-900">conditions générales de vente</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('{{ config('services.stripe.key') }}');
const elements = stripe.elements();
const cardElement = elements.create('card', {
    style: {
        base: {
            fontSize: '16px',
            color: '#32325d',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    }
});

cardElement.mount('#card-element');

const form = document.getElementById('payment-form');
form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const { paymentMethod } = document.querySelector('input[name="payment_method"]:checked').value;
    
    if (paymentMethod === 'card') {
        const { error, paymentMethod } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        });

        if (error) {
            // Gérer l'erreur
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
            return;
        }

        // Ajouter le token au formulaire
        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'payment_method_id');
        hiddenInput.setAttribute('value', paymentMethod.id);
        form.appendChild(hiddenInput);
    }

    // Soumettre le formulaire
    form.submit();
});
</script>
@endpush
