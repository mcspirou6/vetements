@extends('layouts.app')

@section('content')
<div class="container py-5">
    @if(!$cart || count($cart) === 0)
        <div class="alert alert-warning">
            Votre panier est vide. <a href="{{ route('shop') }}" class="alert-link">Continuez vos achats</a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Informations de livraison</h5>
                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="firstname" class="form-label">Prénom</label>
                                    <input type="text" class="form-control @error('firstname') is-invalid @enderror" 
                                           id="firstname" name="firstname" value="{{ old('firstname', auth()->user()->firstname ?? '') }}" required>
                                    @error('firstname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="lastname" class="form-label">Nom</label>
                                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" 
                                           id="lastname" name="lastname" value="{{ old('lastname', auth()->user()->lastname ?? '') }}" required>
                                    @error('lastname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="address" class="form-label">Adresse</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                           id="address" name="address" value="{{ old('address') }}" required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="postal_code" class="form-label">Code postal</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                           id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="city" class="form-label">Ville</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="country" class="form-label">Pays</label>
                                    <select class="form-select @error('country') is-invalid @enderror" 
                                            id="country" name="country" required>
                                        <option value="">Sélectionnez un pays</option>
                                        <option value="FR" {{ old('country') == 'FR' ? 'selected' : '' }}>France</option>
                                        <option value="BE" {{ old('country') == 'BE' ? 'selected' : '' }}>Belgique</option>
                                        <option value="CH" {{ old('country') == 'CH' ? 'selected' : '' }}>Suisse</option>
                                        <option value="LU" {{ old('country') == 'LU' ? 'selected' : '' }}>Luxembourg</option>
                                    </select>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check me-2"></i>Confirmer la commande
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Récapitulatif de la commande</h5>
                        
                        @foreach($cart as $id => $item)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0">{{ $item['name'] }}</h6>
                                    <small class="text-muted">Quantité: {{ $item['quantity'] }}</small>
                                </div>
                                <span>{{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }} €</span>
                            </div>
                        @endforeach

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h6 mb-0">Total</span>
                            <span class="h5 mb-0">{{ number_format($total, 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
