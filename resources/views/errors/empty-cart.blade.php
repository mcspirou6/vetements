@extends('layouts.app')

@section('title', 'Panier Vide')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-shopping-cart text-info" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="card-title mb-4">Votre Panier est Vide</h2>
                    <p class="card-text mb-4">
                        @if(session('error'))
                            {{ session('error') }}
                        @else
                            Vous devez ajouter des articles à votre panier avant de procéder au paiement.
                        @endif
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('shop.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Continuer mes achats
                        </a>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-shopping-cart me-2"></i>Voir mon panier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
