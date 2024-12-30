<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wishlists = auth()->user()->wishlists()->with('product.category')->latest()->get();
        return view('wishlists.index', compact('wishlists'));
    }

    public function store(Request $request, Product $product)
    {
        $wishlist = auth()->user()->wishlists()->where('product_id', $product->id)->first();

        if ($wishlist) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce produit est déjà dans votre liste de souhaits'
                ]);
            }
            return back()->with('error', 'Ce produit est déjà dans votre liste de souhaits');
        }

        auth()->user()->wishlists()->create([
            'product_id' => $product->id
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté à votre liste de souhaits'
            ]);
        }

        return back()->with('success', 'Produit ajouté à votre liste de souhaits');
    }

    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== auth()->id()) {
            abort(403);
        }

        $wishlist->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produit retiré de votre liste de souhaits'
            ]);
        }

        return back()->with('success', 'Produit retiré de votre liste de souhaits');
    }

    public function moveToCart(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== auth()->id()) {
            abort(403);
        }

        $cart = auth()->user()->cart ?? auth()->user()->cart()->create();

        $cart->items()->create([
            'product_id' => $wishlist->product_id,
            'quantity' => 1,
            'size' => $wishlist->product->sizes[0] ?? null,
            'color' => $wishlist->product->colors[0] ?? null
        ]);

        $wishlist->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produit déplacé dans votre panier',
                'cart_count' => $cart->items_count
            ]);
        }

        return back()->with('success', 'Produit déplacé dans votre panier');
    }
}
