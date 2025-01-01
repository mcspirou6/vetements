@extends('layouts.app')

@section('title', 'Boutique')

@section('content')
<div class="container py-5">
    <h1 class="display-4 text-center mb-5">Notre Boutique</h1>

    <!-- Filtres -->
    <div class="row">
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

        <!-- Grille de produits -->
        <div class="col-lg-9">
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-md-3">
                        <div class="card h-100 product-card">
                            <!-- Images du produit -->
                            <div class="position-relative product-images" style="height: 280px;">
                                @if(is_array($product->images) && count($product->images) > 0)
                                    @foreach($product->images as $index => $image)
                                        <img src="{{ asset('storage/' . $image) }}" 
                                             alt="{{ $product->name }}"
                                             class="card-img-top position-absolute w-100 h-100 object-fit-cover transition-opacity 
                                                    {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                                             data-index="{{ $index }}">
                                    @endforeach
                                @else
                                    <img src="{{ asset('images/placeholder.jpg') }}" 
                                         class="card-img-top h-100 w-100 object-fit-cover"
                                         alt="{{ $product->name }}">
                                @endif

                                <!-- Badge promo -->
                                @if($product->sale_price)
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-danger">
                                            -{{ number_format((($product->price - $product->sale_price) / $product->price) * 100, 0) }}%
                                        </span>
                                    </div>
                                @endif

                                <!-- Overlay au survol -->
                                <div class="overlay position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center opacity-0">
                                    <div class="d-flex gap-2">
                                        <button onclick="quickView({{ $product->id }})" 
                                                class="btn btn-light">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('products.show', $product) }}" 
                                           class="btn btn-danger">
                                            <i class="fas fa-plus"></i> Voir plus
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title mb-2">{{ $product->name }}</h5>
                                <div class="mb-2">
                                    @if($product->sale_price)
                                        <span class="text-danger fw-bold">{{ number_format($product->sale_price, 2, ',', ' ') }} €</span>
                                        <span class="text-muted text-decoration-line-through ms-2">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                                    @else
                                        <span class="fw-bold">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                                    @endif
                                </div>
                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit($product->description, 100) }}
                                </p>
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <style>
    .product-card {
        transition: transform 0.2s ease-in-out;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-images img {
        transition: opacity 0.3s ease-in-out;
    }

    .overlay {
        transition: opacity 0.3s ease-in-out;
    }

    .product-card:hover .overlay {
        opacity: 1 !important;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #bb2d3b;
        border-color: #bb2d3b;
    }
    </style>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const productCards = document.querySelectorAll('.product-card');
        
        productCards.forEach(card => {
            const images = card.querySelectorAll('.product-images img');
            if (images.length <= 1) return;
            
            let currentIndex = 0;
            let interval;
            
            const showImage = (index) => {
                images.forEach(img => {
                    img.style.opacity = '0';
                    img.classList.remove('opacity-100');
                });
                images[index].style.opacity = '1';
                images[index].classList.add('opacity-100');
            };
            
            const nextImage = () => {
                currentIndex = (currentIndex + 1) % images.length;
                showImage(currentIndex);
            };
            
            card.addEventListener('mouseenter', () => {
                interval = setInterval(nextImage, 1000);
            });
            
            card.addEventListener('mouseleave', () => {
                clearInterval(interval);
                currentIndex = 0;
                showImage(0);
            });
        });
    });

    function quickView(productId) {
        // Implémenter la logique de vue rapide
    }
    </script>
    @endpush
@endsection
