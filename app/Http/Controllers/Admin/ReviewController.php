<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $reviews = Review::with(['user', 'product'])
            ->latest()
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'product']);
        return view('admin.reviews.show', compact('review'));
    }

    public function approve(Review $review)
    {
        $review->update([
            'is_approved' => true
        ]);

        // Mettre à jour la note moyenne du produit
        $product = $review->product;
        $avgRating = $product->reviews()
            ->where('is_approved', true)
            ->avg('rating');
        
        $product->update([
            'rating' => round($avgRating, 1)
        ]);

        return back()->with('success', 'Avis approuvé avec succès');
    }

    public function reject(Review $review)
    {
        $review->update([
            'is_approved' => false
        ]);

        // Mettre à jour la note moyenne du produit
        $product = $review->product;
        $avgRating = $product->reviews()
            ->where('is_approved', true)
            ->avg('rating');
        
        $product->update([
            'rating' => round($avgRating, 1)
        ]);

        return back()->with('success', 'Avis rejeté avec succès');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        // Mettre à jour la note moyenne du produit
        $product = $review->product;
        $avgRating = $product->reviews()
            ->where('is_approved', true)
            ->avg('rating');
        
        $product->update([
            'rating' => round($avgRating, 1)
        ]);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Avis supprimé avec succès');
    }

    public function filter(Request $request)
    {
        $reviews = Review::with(['user', 'product']);

        if ($request->rating) {
            $reviews->where('rating', $request->rating);
        }

        if ($request->is_approved !== null) {
            $reviews->where('is_approved', $request->boolean('is_approved'));
        }

        if ($request->product_id) {
            $reviews->where('product_id', $request->product_id);
        }

        if ($request->user_id) {
            $reviews->where('user_id', $request->user_id);
        }

        $reviews = $reviews->latest()->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }
}
