@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- En-tête de la catégorie -->
    <div class="mb-8">
        @if($category->image)
        <div class="relative h-64 rounded-lg overflow-hidden mb-6">
            <img src="{{ asset('storage/' . $category->image) }}" 
                alt="{{ $category->name }}" 
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                <h1 class="text-4xl font-bold text-white">{{ $category->name }}</h1>
            </div>
        </div>
        @else
        <h1 class="text-4xl font-bold mb-4">{{ $category->name }}</h1>
        @endif

        @if($category->description)
        <p class="text-gray-600">{{ $category->description }}</p>
        @endif
    </div>

    <!-- Liste des produits -->
    @if($products->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-lg shadow-md overflow-hidden group">
            <a href="{{ route('products.show', $product) }}" class="block relative">
                @if($product->images && count($product->images) > 0)
                <img src="{{ asset('storage/' . $product->images[0]) }}" 
                    alt="{{ $product->name }}" 
                    class="w-full h-64 object-cover group-hover:scale-105 transition duration-300">
                @else
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500">Pas d'image</span>
                </div>
                @endif

                @if($product->sale_price)
                <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm">
                    Promo
                </div>
                @endif
            </a>

            <div class="p-4">
                <h3 class="text-lg font-semibold mb-2">
                    <a href="{{ route('products.show', $product) }}" class="hover:text-gray-600">
                        {{ $product->name }}
                    </a>
                </h3>
                
                <div class="flex justify-between items-center mb-4">
                    <div>
                        @if($product->sale_price)
                        <span class="text-red-600 font-bold">{{ number_format($product->sale_price, 2) }} €</span>
                        <span class="text-gray-400 line-through text-sm ml-2">{{ number_format($product->price, 2) }} €</span>
                        @else
                        <span class="text-gray-900 font-bold">{{ number_format($product->price, 2) }} €</span>
                        @endif
                    </div>
                </div>

                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-black text-white px-4 py-2 rounded hover:bg-gray-800 transition">
                        Ajouter au panier
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
    @else
    <div class="text-center py-12">
        <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun produit dans cette catégorie</h3>
        <p class="text-gray-500">Revenez plus tard pour découvrir nos nouveautés</p>
    </div>
    @endif
</div>
@endsection
