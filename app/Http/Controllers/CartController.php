<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $total = 0;
        
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        if ($product->quantity < 1) {
            return back()->with('error', 'Ce produit est en rupture de stock.');
        }

        $cart = session('cart', []);
        
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
                'image' => $product->image // Utilisation de l'accesseur image
            ];
        }
        
        session(['cart' => $cart]);
        
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

        $cart = session('cart', []);
        
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $request->quantity;
            session(['cart' => $cart]);
        }
        
        return back()->with('success', 'Panier mis à jour !');
    }

    public function remove(Product $product)
    {
        $cart = session('cart', []);
        
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session(['cart' => $cart]);
        }
        
        return back()->with('success', 'Produit retiré du panier !');
    }
}
