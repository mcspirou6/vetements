@extends('layouts.app')

@section('title', 'Facture #' . $order->order_number)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <!-- En-tête de la facture -->
                    <div class="row mb-4">
                        <div class="col-6">
                            <h2 class="mb-4">FACTURE</h2>
                            <h5 class="text-muted mb-1">Facture #{{ $order->order_number }}</h5>
                            <p class="text-muted">Date : {{ $order->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-6 text-end">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mb-3" style="max-height: 50px;">
                            <p class="mb-1"><strong>{{ config('app.name') }}</strong></p>
                            <p class="text-muted mb-0">123 Rue du Commerce</p>
                            <p class="text-muted mb-0">75001 Paris</p>
                            <p class="text-muted mb-0">France</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Informations client -->
                    <div class="row mb-4">
                        <div class="col-6">
                            <h5 class="mb-3">Facturer à</h5>
                            <p class="mb-1"><strong>{{ $order->user->name }}</strong></p>
                            <p class="mb-1">{{ $order->shipping_address }}</p>
                            <p class="mb-1">{{ $order->shipping_postal_code }} {{ $order->shipping_city }}</p>
                            <p class="mb-1">{{ $order->shipping_country }}</p>
                            <p class="mb-1">Tél : {{ $order->shipping_phone }}</p>
                        </div>
                        <div class="col-6 text-end">
                            <h5 class="mb-3">Détails de la commande</h5>
                            <p class="mb-1">Commande #{{ $order->order_number }}</p>
                            <p class="mb-1">Date : {{ $order->created_at->format('d/m/Y') }}</p>
                            <p class="mb-1">Statut : 
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Articles -->
                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Article</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Prix unitaire</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->price, 2) }} €</td>
                                    <td class="text-end">{{ number_format($item->price * $item->quantity, 2) }} €</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total HT</strong></td>
                                    <td class="text-end">{{ number_format($order->total_amount / 1.2, 2) }} €</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>TVA (20%)</strong></td>
                                    <td class="text-end">{{ number_format($order->total_amount - ($order->total_amount / 1.2), 2) }} €</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total TTC</strong></td>
                                    <td class="text-end"><strong>{{ number_format($order->total_amount, 2) }} €</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Notes et conditions -->
                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted mb-0"><small>Conditions de paiement : Paiement à réception de la facture</small></p>
                            <p class="text-muted mb-0"><small>Mode de paiement : {{ ucfirst($order->payment_method) }}</small></p>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                <button onclick="window.print()" class="btn btn-secondary me-2">
                                    <i class="fas fa-print me-2"></i>Imprimer
                                </button>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour à la commande
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .btn, header, footer, nav {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .container {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    body {
        padding: 2cm !important;
    }
}
</style>
@endpush
@endsection
