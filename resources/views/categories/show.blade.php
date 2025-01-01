@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- En-tête de la catégorie avec bannière -->
    <div class="position-relative mb-5">
        <div class="category-banner">
            @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}" 
                     alt="{{ $category->name }}" 
                     class="w-100 h-100 object-fit-cover rounded">
            @endif
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 rounded">
                <div class="text-center">
                    <h1 class="display-4 text-white fw-bold mb-2">{{ $category->name }}</h1>
                    @if($category->description)
                        <p class="text-white fs-5">{{ $category->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Grille de produits -->
    <div class="row g-4">
        @foreach($products as $product)
            <div class="col-md-3">
                <div class="card h-100 product-card">
                    <!-- Images du produit -->
                    <div class="position-relative product-images" style="height: 280px;">
                        @if($product->main_image)
                            <img src="{{ asset('storage/' . $product->main_image) }}" 
                                 alt="{{ $product->name }}"
                                 class="card-img-top position-absolute w-100 h-100 object-fit-cover transition-opacity opacity-100">
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

<style>
.category-banner {
    height: 300px;
    overflow: hidden;
}

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
