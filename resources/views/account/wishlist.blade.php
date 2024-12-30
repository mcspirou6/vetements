@extends('layouts.app')

@section('title', 'Ma liste d\'envies')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Menu latéral -->
        @include('account.partials.sidebar')

        <!-- Contenu principal -->
        <div class="flex-1">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b">
                    <h1 class="text-lg font-medium">Ma liste d'envies</h1>
                </div>

                @if($wishlist->isEmpty())
                    <div class="p-6 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-medium mb-2">Votre liste d'envies est vide</h2>
                        <p class="text-gray-500 mb-4">Parcourez notre boutique et ajoutez des articles à votre liste d'envies</p>
                        <a href="{{ route('shop') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg text-white bg-gray-900 hover:bg-gray-800">
                            Découvrir nos produits
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                        @foreach($wishlist as $item)
                            <div class="group relative">
                                <!-- Badge de stock -->
                                @if(!$item->product->in_stock)
                                    <span class="absolute top-4 right-4 z-10 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Rupture de stock
                                    </span>
                                @endif

                                <!-- Image -->
                                <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-lg bg-gray-200">
                                    <img src="{{ $item->product->image_url }}" 
                                         alt="{{ $item->product->name }}"
                                         class="h-full w-full object-cover object-center group-hover:opacity-75">
                                </div>

                                <!-- Informations produit -->
                                <div class="mt-4">
                                    <h3 class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('products.show', $item->product) }}">
                                            {{ $item->product->name }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $item->product->category->name }}
                                    </p>
                                    <div class="mt-2 flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $item->product->price_formatted }}
                                        </p>
                                        @if($item->product->old_price)
                                            <p class="text-sm text-gray-500 line-through">
                                                {{ $item->product->old_price_formatted }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="mt-4 flex items-center justify-between">
                                    @if($item->product->in_stock)
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                            <button type="submit"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-white bg-gray-900 hover:bg-gray-800">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                </svg>
                                                Ajouter au panier
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('account.wishlist.remove', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-sm text-red-600 hover:text-red-500">
                                            Retirer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($wishlist->hasPages())
                        <div class="p-6 border-t">
                            {{ $wishlist->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
