@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <i class="fas fa-shopping-cart fa-4x mb-4 text-muted"></i>
        <h2 class="mb-4">Votre panier est vide</h2>
        <p class="mb-4">Découvrez nos produits et commencez votre shopping !</p>
        <a href="{{ route('shop') }}" class="btn btn-primary">
            <i class="fas fa-shopping-bag me-2"></i>Continuer mes achats
        </a>
    </div>
</div>
@endsection
