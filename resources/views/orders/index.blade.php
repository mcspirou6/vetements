@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Mes commandes</h1>

    @if($orders->isEmpty())
        <div class="alert alert-info">
            Vous n'avez pas encore de commandes.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Numéro</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status_color }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td>{{ number_format($order->total, 2, ',', ' ') }} €</td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">
                                    Voir le détail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
