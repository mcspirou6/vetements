@extends('admin.layouts.app')

@section('title', 'Gestion des coupons')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Coupons</h1>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouveau coupon
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Valeur</th>
                            <th>Utilisation</th>
                            <th>Date d'expiration</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->code }}</td>
                                <td>{{ $coupon->type === 'fixed' ? 'Montant fixe' : 'Pourcentage' }}</td>
                                <td>
                                    @if($coupon->type === 'fixed')
                                        {{ number_format($coupon->value, 2, ',', ' ') }} €
                                    @else
                                        {{ $coupon->value }}%
                                    @endif
                                </td>
                                <td>{{ $coupon->used_count }} / {{ $coupon->max_uses ?: '∞' }}</td>
                                <td>{{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Jamais' }}</td>
                                <td>
                                    <span class="badge bg-{{ $coupon->active ? 'success' : 'danger' }}">
                                        {{ $coupon->active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.coupons.usage', $coupon) }}" 
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-history"></i>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce coupon ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucun coupon trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($coupons->hasPages())
                <div class="mt-4">
                    {{ $coupons->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
