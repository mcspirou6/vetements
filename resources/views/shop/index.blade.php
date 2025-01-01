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
                    
                    <form action="{{ route('shop') }}" method="GET" id="filter-form">
                        <!-- Catégories -->
                        <div class="mb-4">
                            <h6 class="mb-3">Catégories</h6>
                            @foreach($categories as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input filter-input" type="checkbox" 
                                           name="categories[]" value="{{ $category->id }}"
                                           id="category{{ $category->id }}"
                                           {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category{{ $category->id }}">
                                        {{ $category->name }}
                                        <span class="text-muted">({{ $category->products_count }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Prix -->
                        <div class="mb-4">
                            <h6 class="mb-3">Prix</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control filter-input" 
                                           name="min_price" placeholder="Min €" 
                                           value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control filter-input" 
                                           name="max_price" placeholder="Max €" 
                                           value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Tri -->
                        <div class="mb-4">
                            <h6 class="mb-3">Trier par</h6>
                            <select class="form-select filter-input" name="sort">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom Z-A</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Appliquer les filtres</button>
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
            </div>

            @if($products->isEmpty())
                <div class="alert alert-info">
                    Aucun produit ne correspond à vos critères.
                </div>
            @else
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm product-card">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         class="card-img-top" alt="{{ $product->name }}"
                                         style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title mb-1">
                                        <a href="{{ route('products.show', $product) }}" 
                                           class="text-dark text-decoration-none">
                                            {{ $product->name }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-2">
                                        {{ $product->category->name }}
                                    </p>
                                    <p class="card-text mb-3">
                                        {{ Str::limit($product->description, 100) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 mb-0">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                                        <form action="{{ route('cart.add', $product) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary">
                                                <i class="fas fa-shopping-cart"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-submit form when filters change
    document.querySelectorAll('.filter-input').forEach(input => {
        input.addEventListener('change', () => {
            document.getElementById('filter-form').submit();
        });
    });
</script>
@endpush
@endsection
