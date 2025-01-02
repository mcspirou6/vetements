@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Commande #{{ $order->id }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row">
        <!-- Détails de la commande -->
        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Détails de la commande</h6>
                    <span class="badge {{ $order->status_badge }}">{{ $order->status_label }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Prix unitaire</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="img-thumbnail me-3" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ number_format($item->price, 2) }} €</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->price * $item->quantity, 2) }} €</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td><strong>{{ number_format($order->total_amount, 2) }} €</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations complémentaires -->
        <div class="col-xl-4">
            <!-- Client -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Client</h6>
                </div>
                <div class="card-body">
                    <p><strong>Nom :</strong> {{ $order->user->name }}</p>
                    <p><strong>Email :</strong> {{ $order->user->email }}</p>
                    <p><strong>Date d'inscription :</strong> {{ $order->user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Adresse de livraison -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Adresse de livraison</h6>
                </div>
                <div class="card-body">
                    <address>
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_postal_code }} {{ $order->shipping_city }}<br>
                        {{ $order->shipping_country }}<br>
                        <strong>Tél :</strong> {{ $order->shipping_phone }}
                    </address>
                </div>
            </div>

            <!-- Paiement -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Paiement</h6>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Statut :</strong>
                        <span class="badge {{ $order->payment_status === 'completed' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </p>
                    <p><strong>Méthode :</strong> {{ ucfirst($order->payment_method ?? 'Non spécifié') }}</p>
                    <p><strong>Date :</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>En traitement</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Livrée</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Mettre à jour le statut
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
