<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckoutMiddleware
{
    public function handle($request, Closure $next)
    {
        $cart = Auth::user()->cart;

        // Vérifier si le panier existe et n'est pas vide
        if (!$cart || $cart->items->isEmpty()) {
            return redirect('/empty-cart')
                ->with('error', 'Votre panier est vide.');
        }

        // Vérifier si tous les produits sont toujours disponibles
        foreach ($cart->items as $item) {
            if (!$item->product || $item->product->quantity < $item->quantity) {
                return redirect('/unavailable-products')
                    ->with('error', 'Certains produits de votre panier ne sont plus disponibles en quantité suffisante.');
            }
        }

        return $next($request);
    }
}
