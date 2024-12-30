<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->where('is_active', true)
            ->where('quantity', '>', 0);

        // Filtrage par catégorie
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtrage par prix
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Tri
        switch ($request->get('sort', 'newest')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->take(4)
            ->get();

        $reviews = $product->reviews()->approved()->with('user')->latest()->get();

        return view('products.show', compact('product', 'relatedProducts', 'reviews'));
    }

    public function review(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10'
        ]);

        $product->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false
        ]);

        return back()->with('success', 'Votre avis a été soumis avec succès et est en attente d\'approbation.');
    }

    public function quickView(Product $product)
    {
        return response()->json([
            'success' => true,
            'html' => view('products.quick-view', compact('product'))->render()
        ]);
    }

    public function filter(Request $request)
    {
        $query = Product::active()->inStock();

        if ($request->has('category')) {
            $query->whereIn('category_id', $request->category);
        }

        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
            }
        }

        $products = $query->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('products.list', compact('products'))->render(),
                'pagination' => view('products.pagination', compact('products'))->render()
            ]);
        }

        return view('shop', compact('products'));
    }
}
