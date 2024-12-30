@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar avec filtres -->
        <div class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Filtres</h2>
                
                <form action="{{ route('products.index') }}" method="GET">
                    <!-- Catégories -->
                    <div class="mb-6">
                        <h3 class="font-medium mb-3">Catégories</h3>
                        @foreach($categories as $category)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="category[]" value="{{ $category->slug }}"
                                {{ in_array($category->slug, request()->get('category', [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-black focus:ring-black">
                            <label class="ml-2 text-gray-700">{{ $category->name }}</label>
                        </div>
                        @endforeach
                    </div>

                    <!-- Prix -->
                    <div class="mb-6">
                        <h3 class="font-medium mb-3">Prix</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm text-gray-600">Min</label>
                                <input type="number" name="min_price" value="{{ request('min_price') }}"
                                    class="w-full mt-1 rounded-md border-gray-300 focus:border-black focus:ring-black">
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">Max</label>
                                <input type="number" name="max_price" value="{{ request('max_price') }}"
                                    class="w-full mt-1 rounded-md border-gray-300 focus:border-black focus:ring-black">
                            </div>
                        </div>
                    </div>

                    <!-- Tri -->
                    <div class="mb-6">
                        <h3 class="font-medium mb-3">Trier par</h3>
                        <select name="sort" class="w-full rounded-md border-gray-300 focus:border-black focus:ring-black">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom Z-A</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-black text-white py-2 px-4 rounded-md hover:bg-gray-800 transition">
                        Appliquer les filtres
                    </button>
                </form>
            </div>
        </div>

        <!-- Liste des produits -->
        <div class="flex-1">
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
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
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun produit trouvé</h3>
                    <p class="text-gray-500">Essayez de modifier vos filtres de recherche</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
