@extends('layouts.app')

@section('title', 'Produits Non Disponibles')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="card-title mb-4">Produits Non Disponibles</h2>
                    <p class="card-text mb-4">
                        @if(session('error'))
                            {{ session('error') }}
                        @else
                            Certains produits de votre panier ne sont plus disponibles ou la quantité demandée dépasse le stock.
                        @endif
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('cart.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>Modifier mon panier
                        </a>
                        <a href="{{ route('shop.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Continuer mes achats
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
