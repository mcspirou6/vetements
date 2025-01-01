@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Créer un nouveau coupon</h1>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="code" class="form-label">Code du coupon</label>
                    <div class="input-group">
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code') }}" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="generateCode()">
                            Générer
                        </button>
                    </div>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type de réduction</label>
                    <select class="form-select @error('type') is-invalid @enderror" 
                            id="type" name="type" required>
                        <option value="">Sélectionner un type</option>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>
                            Montant fixe
                        </option>
                        <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>
                            Pourcentage
                        </option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="value" class="form-label">Valeur</label>
                    <div class="input-group">
                        <input type="number" step="0.01" class="form-control @error('value') is-invalid @enderror" 
                               id="value" name="value" value="{{ old('value') }}" required>
                        <span class="input-group-text value-symbol">€</span>
                    </div>
                    @error('value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="min_purchase" class="form-label">Montant minimum d'achat</label>
                    <div class="input-group">
                        <input type="number" step="0.01" class="form-control @error('min_purchase') is-invalid @enderror" 
                               id="min_purchase" name="min_purchase" value="{{ old('min_purchase') }}">
                        <span class="input-group-text">€</span>
                    </div>
                    @error('min_purchase')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="starts_at" class="form-label">Date de début</label>
                            <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" 
                                   id="starts_at" name="starts_at" value="{{ old('starts_at') }}">
                            @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Date d'expiration</label>
                            <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="max_uses" class="form-label">Nombre maximum d'utilisations</label>
                    <input type="number" class="form-control @error('max_uses') is-invalid @enderror" 
                           id="max_uses" name="max_uses" value="{{ old('max_uses') }}">
                    @error('max_uses')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Actif</label>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-primary">Créer le coupon</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function generateCode() {
    // Générer un code aléatoire
    const length = 8;
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < length; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('code').value = code;
}

// Mettre à jour le symbole en fonction du type de réduction
document.getElementById('type').addEventListener('change', function() {
    const symbol = this.value === 'percent' ? '%' : '€';
    document.querySelector('.value-symbol').textContent = symbol;
});
</script>
@endpush
@endsection
