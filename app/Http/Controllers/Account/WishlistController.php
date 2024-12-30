<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $wishlist = auth()->user()->wishlistItems()
            ->with('product.category')
            ->latest()
            ->paginate(12);

        return view('account.wishlist', compact('wishlist'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Vérifie si le produit est déjà dans la liste
        $exists = auth()->user()->wishlistItems()
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Ce produit est déjà dans votre liste d\'envies.');
        }

        auth()->user()->wishlistItems()->create([
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Le produit a été ajouté à votre liste d\'envies.');
    }

    public function destroy(WishlistItem $item)
    {
        $this->authorize('delete', $item);

        $item->delete();

        return back()->with('success', 'Le produit a été retiré de votre liste d\'envies.');
    }

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $item = auth()->user()->wishlistItems()
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->delete();
            $message = 'Le produit a été retiré de votre liste d\'envies.';
            $added = false;
        } else {
            auth()->user()->wishlistItems()->create([
                'product_id' => $product->id,
            ]);
            $message = 'Le produit a été ajouté à votre liste d\'envies.';
            $added = true;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'added' => $added,
            ]);
        }

        return back()->with('success', $message);
    }
}
