@extends('layouts.app')

@section('title', 'Mes commandes')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Menu latéral -->
        @include('account.partials.sidebar')

        <!-- Contenu principal -->
        <div class="flex-1">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b">
                    <h1 class="text-lg font-medium">Mes commandes</h1>
                </div>

                @if($orders->isEmpty())
                    <div class="p-6 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-medium mb-2">Aucune commande</h2>
                        <p class="text-gray-500 mb-4">Vous n'avez pas encore passé de commande</p>
                        <a href="{{ route('shop') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg text-white bg-gray-900 hover:bg-gray-800">
                            Découvrir nos produits
                        </a>
                    </div>
                @else
                    <div class="divide-y">
                        @foreach($orders as $order)
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <div class="flex items-center">
                                            <span class="font-medium">#{{ $order->reference }}</span>
                                            <span class="mx-2">•</span>
                                            <span class="text-gray-500">
                                                {{ $order->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            {{ $order->items_count }} article(s) • {{ $order->total_formatted }}
                                        </div>
                                    </div>
                                    <div class="mt-4 md:mt-0 flex items-center gap-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                   {{ $order->status_color }} {{ $order->status_bg }}">
                                            {{ $order->status_label }}
                                        </span>
                                        <a href="{{ route('account.orders.show', $order) }}" 
                                           class="text-gray-600 hover:text-gray-900">
                                            Voir les détails →
                                        </a>
                                    </div>
                                </div>

                                <!-- Aperçu des articles -->
                                <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                    @foreach($order->items->take(6) as $item)
                                        <div class="relative group">
                                            <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-lg bg-gray-200">
                                                <img src="{{ $item->product->image_url }}" 
                                                     alt="{{ $item->product->name }}"
                                                     class="h-full w-full object-cover object-center">
                                            </div>
                                            @if($loop->iteration === 6 && $order->items_count > 6)
                                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-lg">
                                                    <span class="text-white font-medium">
                                                        +{{ $order->items_count - 5 }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Actions -->
                                <div class="mt-4 flex flex-wrap gap-4">
                                    @if($order->can_be_cancelled)
                                        <form action="{{ route('account.orders.cancel', $order) }}" 
                                              method="POST"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-sm text-red-600 hover:text-red-500">
                                                Annuler la commande
                                            </button>
                                        </form>
                                    @endif

                                    @if($order->invoice_url)
                                        <a href="{{ $order->invoice_url }}" 
                                           target="_blank"
                                           class="text-sm text-gray-600 hover:text-gray-900">
                                            Télécharger la facture
                                        </a>
                                    @endif

                                    @if($order->can_be_returned)
                                        <a href="{{ route('account.orders.return', $order) }}" 
                                           class="text-sm text-gray-600 hover:text-gray-900">
                                            Retourner un article
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="p-6 border-t">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
