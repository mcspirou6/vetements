@extends('layouts.app')

@section('title', 'Accès Non Autorisé')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-lock text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="card-title mb-4">Accès Non Autorisé</h2>
                    <p class="card-text mb-4">
                        @if(session('error'))
                            {{ session('error') }}
                        @else
                            Cette section est réservée aux administrateurs.
                        @endif
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Retour à l'accueil
                        </a>
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
