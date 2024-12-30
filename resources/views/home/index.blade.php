@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gray-900 text-white">
        <div class="absolute inset-0">
            <img src="{{ asset('images/hero-bg.jpg') }}" alt="Hero background" class="w-full h-full object-cover opacity-50">
        </div>
        <div class="relative container mx-auto px-6 py-32">
            <h1 class="text-5xl font-bold mb-4">Découvrez notre nouvelle collection</h1>
            <p class="text-xl mb-8">Des vêtements de qualité pour tous les styles</p>
            <a href="{{ route('shop') }}" class="bg-white text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                Voir la collection
            </a>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12">Nos catégories</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach(\App\Models\Category::where('featured', true)->take(3)->get() as $category)
                    <a href="{{ route('category', $category) }}" class="group">
                        <div class="relative overflow-hidden rounded-lg shadow-lg">
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" 
                                 class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                <h3 class="text-2xl font-bold text-white">{{ $category->name }}</h3>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12">Produits populaires</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach(\App\Models\Product::where('featured', true)->take(8)->get() as $product)
                    <div class="group">
                        <div class="relative overflow-hidden rounded-lg mb-3">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                 class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-300">
                            <!-- Quick view button -->
                            <button onclick="quickView('{{ $product->id }}')" 
                                    class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="bg-white text-gray-900 px-4 py-2 rounded-lg">Aperçu rapide</span>
                            </button>
                            <!-- Wishlist button -->
                            @auth
                                <button onclick="toggleWishlist('{{ $product->id }}')"
                                        class="absolute top-2 right-2 p-2 rounded-full bg-white shadow-md hover:bg-gray-100">
                                    <svg class="w-6 h-6 {{ auth()->user()->wishlists->contains('product_id', $product->id) ? 'text-red-500' : 'text-gray-400' }}"
                                         fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                </button>
                            @endauth
                        </div>
                        <h3 class="font-semibold text-lg mb-1">{{ $product->name }}</h3>
                        <p class="text-gray-600 mb-2">{{ $product->price_formatted }}</p>
                        <button onclick="addToCart('{{ $product->id }}')"
                                class="w-full bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors">
                            Ajouter au panier
                        </button>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('shop') }}" class="inline-block border-2 border-gray-900 text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-900 hover:text-white transition-colors">
                    Voir tous les produits
                </a>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">Livraison gratuite</h3>
                    <p class="text-gray-600">Pour toute commande supérieure à 100€</p>
                </div>
                <div class="text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">Paiement sécurisé</h3>
                    <p class="text-gray-600">Transactions 100% sécurisées</p>
                </div>
                <div class="text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">Retours gratuits</h3>
                    <p class="text-gray-600">Sous 30 jours</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-16">
        <div class="container mx-auto px-6">
            <div class="bg-gray-900 rounded-lg py-12 px-6 md:px-12 text-center text-white">
                <h2 class="text-3xl font-bold mb-4">Restez informé</h2>
                <p class="text-lg mb-8">Inscrivez-vous à notre newsletter pour recevoir nos dernières offres</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="max-w-lg mx-auto">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-4">
                        <input type="email" name="email" required 
                               class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:outline-none" 
                               placeholder="Votre adresse email">
                        <button type="submit" 
                                class="px-8 py-3 bg-white text-gray-900 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            S'inscrire
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function quickView(productId) {
        // Implémenter la logique de vue rapide
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
                // Mettre à jour l'interface
                const button = event.currentTarget.querySelector('svg');
                button.classList.toggle('text-red-500');
                button.classList.toggle('text-gray-400');
            }
        });
    }

    function addToCart(productId) {
        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour le compteur du panier
                const cartCount = document.querySelector('#cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cartCount;
                }
                // Afficher une notification
                alert('Produit ajouté au panier');
            }
        });
    }
</script>
@endpush