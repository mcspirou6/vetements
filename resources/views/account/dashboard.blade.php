@extends('layouts.app')

@section('title', 'Mon compte')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Menu latéral -->
        <div class="lg:w-64">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b">
                    <div class="flex items-center">
                        <img class="w-12 h-12 rounded-full" 
                             src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                             alt="{{ auth()->user()->name }}">
                        <div class="ml-3">
                            <h2 class="font-medium">{{ auth()->user()->name }}</h2>
                            <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>

                <nav class="p-4">
                    <div class="space-y-2">
                        <a href="{{ route('account.dashboard') }}" 
                           class="flex items-center px-4 py-2 rounded-lg text-gray-900 bg-gray-100">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Tableau de bord
                        </a>

                        <a href="{{ route('account.orders') }}" 
                           class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Mes commandes
                        </a>

                        <a href="{{ route('account.addresses') }}" 
                           class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Mes adresses
                        </a>

                        <a href="{{ route('account.wishlist') }}" 
                           class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            Ma liste d'envies
                        </a>

                        <a href="{{ route('account.settings') }}" 
                           class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Paramètres
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center w-full px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="flex-1">
            <!-- Résumé -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Commandes -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium">{{ $orders_count }}</h3>
                            <p class="text-gray-500">Commandes passées</p>
                        </div>
                    </div>
                </div>

                <!-- Liste d'envies -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium">{{ $wishlist_count }}</h3>
                            <p class="text-gray-500">Articles favoris</p>
                        </div>
                    </div>
                </div>

                <!-- Points fidélité -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium">{{ $loyalty_points }}</h3>
                            <p class="text-gray-500">Points fidélité</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dernières commandes -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-medium">Dernières commandes</h2>
                        <a href="{{ route('account.orders') }}" class="text-gray-600 hover:text-gray-900">
                            Voir tout
                        </a>
                    </div>
                </div>

                @if($recent_orders->isEmpty())
                    <div class="p-6 text-center text-gray-500">
                        Vous n'avez pas encore passé de commande
                    </div>
                @else
                    <div class="divide-y">
                        @foreach($recent_orders as $order)
                            <div class="p-6">
                                <div class="flex items-center justify-between">
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
                                    <div class="flex items-center gap-4">
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
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Articles favoris -->
            <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-medium">Articles favoris</h2>
                        <a href="{{ route('account.wishlist') }}" class="text-gray-600 hover:text-gray-900">
                            Voir tout
                        </a>
                    </div>
                </div>

                @if($recent_wishlist->isEmpty())
                    <div class="p-6 text-center text-gray-500">
                        Vous n'avez pas encore d'articles en favoris
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                        @foreach($recent_wishlist as $item)
                            <div class="group">
                                <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-lg bg-gray-200">
                                    <img src="{{ $item->product->image_url }}" 
                                         alt="{{ $item->product->name }}"
                                         class="h-full w-full object-cover object-center group-hover:opacity-75">
                                </div>
                                <div class="mt-4">
                                    <h3 class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('products.show', $item->product) }}">
                                            {{ $item->product->name }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $item->product->price_formatted }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
