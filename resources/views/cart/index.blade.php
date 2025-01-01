@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Mon panier</h1>

    @php
        $cart = session()->get('cart', []);
    @endphp

    @if(empty($cart))
        <div class="alert alert-info">
            Votre panier est vide.
            <a href="{{ route('home') }}" class="alert-link">Continuer mes achats</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0 @endphp
                    @foreach($cart as $id => $details)
                        @php $total += $details['price'] * $details['quantity'] @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($details['image'])
                                        <img src="{{ asset('storage/' . $details['image']) }}" 
                                             alt="{{ $details['name'] }}" 
                                             class="img-thumbnail me-3"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $details['name'] }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>{{ number_format($details['price'], 2, ',', ' ') }} €</td>
                            <td>
                                <form action="{{ route('cart.update', $id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <div class="input-group" style="width: 120px;">
                                        <input type="number" name="quantity" value="{{ $details['quantity'] }}" 
                                               class="form-control" min="1">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                            <td>{{ number_format($details['price'] * $details['quantity'], 2, ',', ' ') }} €</td>
                            <td>
                                <form action="{{ route('cart.remove', $id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                        <td><strong>{{ number_format($total, 2, ',', ' ') }} €</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('home') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Continuer mes achats
            </a>
            <a href="{{ route('checkout.index') }}" class="btn btn-primary">
                Passer la commande<i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    @endif
</div>
@endsection
