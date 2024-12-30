@extends('layouts.app')

@section('title', 'Compte Inactif')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-user-slash text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="card-title mb-4">Compte Inactif</h2>
                    <p class="card-text mb-4">
                        @if(session('error'))
                            {{ session('error') }}
                        @else
                            Votre compte a été désactivé. Veuillez contacter l'administrateur pour plus d'informations.
                        @endif
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Retour à l'accueil
                        </a>
                        <a href="{{ route('contact.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i>Contacter l'administrateur
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
