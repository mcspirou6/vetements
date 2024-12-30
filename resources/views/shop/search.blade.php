@extends('layouts.app')

@section('title', 'Recherche')

@section('content')
<div class="container py-5">
    <!-- En-tête de recherche -->
    <div class="mb-5">
        <h1 class="h3 mb-3">Résultats de recherche pour "{{ request('q') }}"</h1>
        <p class="text-muted">{{ $products->total() }} produit(s) trouvé(s)</p>
    </div>

    <!-- Formulaire de recherche -->
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <form action="{{ route('shop.search') }}" method="GET" class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="q" class="form-control form-control-lg" 
                           value="{{ request('q') }}" placeholder="Rechercher un produit...">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-search me-2"></i>Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Résultats -->
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-3">
                <div class="card h-100 product-card">
                    <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2">{{ $product->name }}</h5>
                        <p class="text-muted mb-2">{{ $product->category->name }}</p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="product-price">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucun produit ne correspond à votre recherche.
                    <a href="{{ route('shop') }}" class="alert-link">Voir tous les produits</a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $products->links() }}
    </div>
</div>
@endsection
