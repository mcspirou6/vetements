@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Découvrez notre nouvelle collection</h1>
                <p class="lead mb-4">Des vêtements de qualité pour tous les styles et toutes les occasions.</p>
                <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag me-2"></i> Découvrir
                </a>
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/600x400" alt="Collection" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>
</section>

<!-- Catégories populaires -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">Catégories populaires</h2>
        <div class="row g-4">
            @foreach($categories->take(4) as $category)
                <div class="col-6 col-md-3">
                    <a href="{{ route('category', $category) }}" class="text-decoration-none">
                        <div class="card category-card h-100">
                            <img src="{{ $category->image_url ?? 'https://via.placeholder.com/300x200' }}" 
                                 class="card-img-top" alt="{{ $category->name }}">
                            <div class="card-body text-center">
                                <h5 class="card-title mb-0">{{ $category->name }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Produits en vedette -->
<section class="bg-light py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">Produits en vedette</h2>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
                <div class="col-md-3">
                    <div class="card h-100 product-card">
                        <div class="position-relative">
                            <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                            @if($product->discount_percentage > 0)
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-danger">-{{ $product->discount_percentage }}%</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2">{{ $product->name }}</h5>
                            <p class="text-muted mb-2">{{ $product->category->name }}</p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($product->old_price)
                                            <small class="text-muted text-decoration-line-through">
                                                {{ number_format($product->old_price, 2, ',', ' ') }} €
                                            </small>
                                        @endif
                                        <span class="product-price ms-2">
                                            {{ number_format($product->price, 2, ',', ' ') }} €
                                        </span>
                                    </div>
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
            @endforeach
        </div>
    </div>
</section>

<!-- Avantages -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center">
                    <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                    <h5>Livraison gratuite</h5>
                    <p class="text-muted">Pour toute commande supérieure à 50€</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <i class="fas fa-undo fa-3x text-primary mb-3"></i>
                    <h5>Retours gratuits</h5>
                    <p class="text-muted">Sous 30 jours</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <i class="fas fa-lock fa-3x text-primary mb-3"></i>
                    <h5>Paiement sécurisé</h5>
                    <p class="text-muted">Par carte ou PayPal</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                    <h5>Service client</h5>
                    <p class="text-muted">24/7 à votre service</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h2 class="mb-4">Inscrivez-vous à notre newsletter</h2>
                <p class="mb-4">Recevez nos dernières offres et nouveautés directement dans votre boîte mail.</p>
                <form action="#" method="POST" class="row g-2 justify-content-center">
                    @csrf
                    <div class="col-auto flex-grow-1" style="max-width: 400px;">
                        <input type="email" class="form-control form-control-lg" placeholder="Votre adresse email">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-light btn-lg">S'inscrire</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
