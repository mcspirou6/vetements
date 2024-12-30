@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container mx-auto px-6 py-8">
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900">
                        Accueil
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('shop') }}" class="text-gray-700 hover:text-gray-900">
                            Boutique
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-500" aria-current="page">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col md:flex-row -mx-4">
            <!-- Galerie d'images -->
            <div class="md:flex-1 px-4">
                <div class="h-[460px] rounded-lg bg-gray-100 mb-4">
                    <img class="w-full h-full object-contain" 
                         src="{{ $product->images->first()->url }}" 
                         alt="{{ $product->name }}"
                         id="main-image">
                </div>
                <div class="flex -mx-2 mb-4">
                    @foreach($product->images as $image)
                        <div class="flex-1 px-2">
                            <button onclick="setMainImage('{{ $image->url }}')"
                                    class="focus:outline-none w-full rounded-lg h-24 md:h-32 bg-gray-100 flex items-center justify-center">
                                <img class="w-full h-full object-contain" 
                                     src="{{ $image->url }}" 
                                     alt="{{ $product->name }}">
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Informations produit -->
            <div class="md:flex-1 px-4">
                <h2 class="text-3xl font-bold mb-2">{{ $product->name }}</h2>
                <div class="flex items-center mb-4">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-gray-600 ml-2">{{ $product->reviews_count }} avis</span>
                </div>

                <div class="flex items-center mb-4">
                    <span class="text-3xl font-bold text-gray-900">{{ $product->price_formatted }}</span>
                    @if($product->old_price)
                        <span class="text-xl text-gray-500 line-through ml-2">{{ $product->old_price_formatted }}</span>
                        <span class="ml-2 px-2 py-1 bg-red-500 text-white text-sm rounded">
                            -{{ $product->discount_percentage }}%
                        </span>
                    @endif
                </div>

                <div class="mb-4">
                    <h3 class="text-xl font-semibold mb-2">Description</h3>
                    <p class="text-gray-600">{{ $product->description }}</p>
                </div>

                <!-- Options du produit -->
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                    @csrf
                    <!-- Tailles -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Taille
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->sizes as $size)
                                <label class="relative">
                                    <input type="radio" name="size" value="{{ $size->id }}" 
                                           class="sr-only peer" required>
                                    <div class="w-14 h-14 rounded-lg border-2 flex items-center justify-center cursor-pointer
                                                peer-checked:bg-gray-900 peer-checked:text-white
                                                {{ $size->pivot->stock > 0 ? 'hover:bg-gray-100' : 'opacity-50 cursor-not-allowed' }}">
                                        {{ $size->name }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Couleurs -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Couleur
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->colors as $color)
                                <label class="relative">
                                    <input type="radio" name="color" value="{{ $color->id }}"
                                           class="sr-only peer" required>
                                    <div class="w-8 h-8 rounded-full border-2 cursor-pointer
                                                peer-checked:ring-2 peer-checked:ring-gray-900 peer-checked:ring-offset-2"
                                         style="background-color: {{ $color->hex_value }}">
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quantité -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Quantité
                        </label>
                        <div class="flex items-center">
                            <button type="button" onclick="decrementQuantity()"
                                    class="w-10 h-10 border rounded-l-lg flex items-center justify-center hover:bg-gray-100">
                                -
                            </button>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                   class="w-20 h-10 border-t border-b text-center [-moz-appearance:_textfield] [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none">
                            <button type="button" onclick="incrementQuantity()"
                                    class="w-10 h-10 border rounded-r-lg flex items-center justify-center hover:bg-gray-100">
                                +
                            </button>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4">
                        <button type="submit"
                                class="flex-1 bg-gray-900 text-white py-3 px-6 rounded-lg hover:bg-gray-800 transition-colors">
                            Ajouter au panier
                        </button>
                        @auth
                            <button type="button" onclick="toggleWishlist('{{ $product->id }}')"
                                    class="w-12 h-12 rounded-lg border-2 flex items-center justify-center hover:bg-gray-100">
                                <svg class="w-6 h-6 {{ auth()->user()->wishlists->contains('product_id', $product->id) ? 'text-red-500' : 'text-gray-400' }}"
                                     fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>
                        @endauth
                    </div>
                </form>

                <!-- Informations supplémentaires -->
                <div class="border-t pt-4">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-600">Livraison gratuite à partir de 100€</span>
                    </div>
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-600">Retours gratuits sous 30 jours</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-600">Paiement sécurisé</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglets description et avis -->
        @include('products.partials.tabs')
    </div>
@endsection

@push('scripts')
<script>
    function setMainImage(url) {
        document.getElementById('main-image').src = url;
    }

    function decrementQuantity() {
        const input = document.querySelector('input[name="quantity"]');
        if (input.value > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }

    function incrementQuantity() {
        const input = document.querySelector('input[name="quantity"]');
        const max = parseInt(input.getAttribute('max'));
        if (parseInt(input.value) < max) {
            input.value = parseInt(input.value) + 1;
        }
    }

    function toggleWishlist(productId) {
        fetch(`/wishlist/add/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const button = event.currentTarget.querySelector('svg');
                button.classList.toggle('text-red-500');
                button.classList.toggle('text-gray-400');
            }
        });
    }
</script>
@endpush
