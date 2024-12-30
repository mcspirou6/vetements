@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Commande #{{ $order->id }}</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
            Retour aux commandes
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Informations de la commande</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Statut :</strong> 
                        <span class="badge bg-{{ $order->status_color }}">
                            {{ $order->status_label }}
                        </span>
                    </p>
                    <p><strong>Total :</strong> {{ number_format($order->total, 2, ',', ' ') }} €</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Adresse de livraison :</strong></p>
                    <address>
                        {{ $order->shipping_address->full_name }}<br>
                        {{ $order->shipping_address->address }}<br>
                        {{ $order->shipping_address->postal_code }} {{ $order->shipping_address->city }}<br>
                        {{ $order->shipping_address->country }}
                    </address>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Produits commandés</h5>
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
                        @foreach($order->products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->images && is_array($product->images) && count($product->images) > 0)
                                            <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="img-thumbnail me-3"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $product->name }}</h6>
                                            @if($product->pivot->options)
                                                <small class="text-muted">
                                                    {{ implode(', ', $product->pivot->options) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($product->pivot->price, 2, ',', ' ') }} €</td>
                                <td>{{ $product->pivot->quantity }}</td>
                                <td>{{ number_format($product->pivot->price * $product->pivot->quantity, 2, ',', ' ') }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Sous-total</strong></td>
                            <td>{{ number_format($order->subtotal, 2, ',', ' ') }} €</td>
                        </tr>
                        @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="3" class="text-end"><strong>Réduction</strong></td>
                                <td>-{{ number_format($order->discount_amount, 2, ',', ' ') }} €</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="text-end"><strong>Livraison</strong></td>
                            <td>{{ number_format($order->shipping_cost, 2, ',', ' ') }} €</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                            <td><strong>{{ number_format($order->total, 2, ',', ' ') }} €</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
