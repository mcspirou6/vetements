<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index');
    }

    public function add(Request $request, Product $product)
    {
        if ($product->quantity < 1) {
            return back()->with('error', 'Ce produit est en rupture de stock.');
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            if ($cart[$product->id]['quantity'] + 1 > $product->quantity) {
                return back()->with('error', 'Stock insuffisant.');
            }
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->sale_price ?? $product->price,
                'image' => $product->images[0] ?? null
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Produit ajouté au panier !');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($request->quantity > $product->quantity) {
            return back()->with('error', 'Stock insuffisant.');
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Panier mis à jour !');
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Produit retiré du panier !');
    }
}
