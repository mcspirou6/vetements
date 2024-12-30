<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::slug($request->name) . '-' . time() . '.' . $image->extension();
            $path = $image->storeAs('products', $imageName, 'public');
            $validatedData['image'] = $path;
        }

        // Gérer les booléens
        $validatedData['is_active'] = $request->has('is_active');
        $validatedData['is_featured'] = $request->has('is_featured');

        Product::create($validatedData);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé avec succès');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('image');
            $imageName = Str::slug($request->name) . '-' . time() . '.' . $image->extension();
            $path = $image->storeAs('products', $imageName, 'public');
            $validatedData['image'] = $path;
        }

        // Gérer les booléens
        $validatedData['is_active'] = $request->has('is_active');
        $validatedData['is_featured'] = $request->has('is_featured');

        $product->update($validatedData);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour avec succès');
    }

    public function destroy(Product $product)
    {
        // Supprimer l'image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé avec succès');
    }

    public function updateFeatured(Request $request, Product $product)
    {
        $product->update([
            'is_featured' => $request->is_featured
        ]);

        return response()->json(['success' => true]);
    }

    public function updateActive(Request $request, Product $product)
    {
        $product->update([
            'is_active' => $request->is_active
        ]);

        return response()->json(['success' => true]);
    }
}
