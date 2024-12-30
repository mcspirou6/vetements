@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-placeholder mb-3">
                            <i class="fas fa-user-circle fa-4x text-muted"></i>
                        </div>
                        <h5 class="card-title mb-0">{{ Auth::user()->name }}</h5>
                        <p class="text-muted small">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-user-edit me-2"></i> Modifier le profil
                        </a>
                        <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-shopping-bag me-2"></i> Mes commandes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-4">Informations du profil</h4>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('profile.update') }}" class="needs-validation" novalidate>
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Sauvegarder
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">Supprimer le compte</h4>
                    <p class="text-muted mb-4">
                        Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.
                    </p>

                    <form method="post" action="{{ route('profile.destroy') }}" class="needs-validation" novalidate>
                        @csrf
                        @method('delete')

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?')">
                                <i class="fas fa-trash-alt me-2"></i> Supprimer le compte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
