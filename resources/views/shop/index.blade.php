@extends('layouts.app')

@section('title', 'Boutique')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Filtres -->
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Filtres</h5>
                    
                    <form action="{{ route('shop') }}" method="GET">
                        <!-- Catégories -->
                        <div class="mb-4">
                            <h6 class="mb-3">Catégories</h6>
                            @foreach($categories as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="categories[]" 
                                           value="{{ $category->id }}" id="category{{ $category->id }}"
                                           {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Prix -->
                        <div class="mb-4">
                            <h6 class="mb-3">Prix</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="min_price" 
                                           placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="max_price" 
                                           placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Tri -->
                        <div class="mb-4">
                            <h6 class="mb-3">Trier par</h6>
                            <select class="form-select" name="sort">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom Z-A</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i> Appliquer les filtres
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Produits -->
        <div class="col-lg-9">
            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Nos produits</h1>
                    <p class="text-muted mb-0">{{ $products->total() }} produits trouvés</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary active">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Liste des produits -->
            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-md-4">
                        <div class="card h-100 product-card">
                            <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-2">{{ $product->name }}</h5>
                                <p class="text-muted mb-2">{{ $product->category->name }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="product-price">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-shopping-cart me-1"></i> Ajouter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Aucun produit trouvé
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
