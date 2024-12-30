@extends('layouts.app')

@section('title', 'Confirmation de commande')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold mb-2">Merci pour votre commande !</h1>
            <p class="text-gray-600">
                Votre commande #{{ $order->reference }} a été confirmée et sera traitée dans les plus brefs délais.
            </p>
        </div>

        <!-- Détails de la commande -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- En-tête -->
            <div class="border-b p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                    <div>
                        <h2 class="text-lg font-medium">Détails de la commande</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Commandé le {{ $order->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                   {{ $order->status_color }} {{ $order->status_bg }}">
                            {{ $order->status_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Articles -->
            <div class="p-6 border-b">
                <div class="divide-y">
                    @foreach($order->items as $item)
                        <div class="py-4 flex gap-4">
                            <img src="{{ $item->product->image_url }}" 
                                 alt="{{ $item->product->name }}"
                                 class="w-20 h-20 object-cover rounded-lg">
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <div>
                                        <h3 class="font-medium">{{ $item->product->name }}</h3>
                                        <div class="text-sm text-gray-500 mt-1">
                                            <span>Taille: {{ $item->size->name }}</span>
                                            <span class="mx-1">•</span>
                                            <span>Couleur: {{ $item->color->name }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-medium">{{ $item->price_formatted }}</div>
                                        <div class="text-sm text-gray-500">Quantité: {{ $item->quantity }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Résumé -->
            <div class="p-6 bg-gray-50">
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sous-total</span>
                        <span>{{ $order->subtotal_formatted }}</span>
                    </div>

                    @if($order->discount > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Réduction</span>
                            <span class="text-green-600">-{{ $order->discount_formatted }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-gray-600">Livraison</span>
                        <span>{{ $order->shipping_formatted }}</span>
                    </div>

                    @if($order->tax > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">TVA</span>
                            <span>{{ $order->tax_formatted }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between pt-4 border-t font-medium text-lg">
                        <span>Total</span>
                        <span>{{ $order->total_formatted }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations de livraison et facturation -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Adresse de livraison -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-medium mb-4">Adresse de livraison</h3>
                <address class="text-gray-600 not-italic">
                    {{ $order->shipping_address->fullname }}<br>
                    {{ $order->shipping_address->address }}<br>
                    @if($order->shipping_address->address_complement)
                        {{ $order->shipping_address->address_complement }}<br>
                    @endif
                    {{ $order->shipping_address->postal_code }} {{ $order->shipping_address->city }}<br>
                    {{ $order->shipping_address->country_name }}
                </address>
            </div>

            <!-- Mode de paiement -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-medium mb-4">Mode de paiement</h3>
                <div class="text-gray-600">
                    @if($order->payment_method === 'card')
                        <div class="flex items-center">
                            @if($order->card_brand === 'visa')
                                <img src="/images/payments/visa.svg" alt="Visa" class="h-8 mr-2">
                            @elseif($order->card_brand === 'mastercard')
                                <img src="/images/payments/mastercard.svg" alt="Mastercard" class="h-8 mr-2">
                            @endif
                            •••• •••• •••• {{ $order->card_last4 }}
                        </div>
                    @else
                        <div class="flex items-center">
                            <img src="/images/payments/paypal.svg" alt="PayPal" class="h-8 mr-2">
                            {{ $order->paypal_email }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('orders.show', $order) }}" 
               class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 shadow-sm rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Suivre ma commande
            </a>
            <a href="{{ route('shop') }}"
               class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-lg text-white bg-gray-900 hover:bg-gray-800 focus:outline-none">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Continuer mes achats
            </a>
        </div>

        <!-- Aide -->
        <div class="mt-8 text-center">
            <p class="text-gray-600">
                Besoin d'aide ? 
                <a href="{{ route('contact') }}" class="text-gray-900 underline">Contactez-nous</a>
            </p>
        </div>
    </div>
</div>
@endsection
