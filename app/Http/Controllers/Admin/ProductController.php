<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'sizes' => 'nullable|array',
                'colors' => 'nullable|array',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Gérer les images
            $images = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $images[] = $path;
                }
            }

            $validatedData['images'] = $images;
            $validatedData['slug'] = Str::slug($validatedData['name']);

            $product = Product::create($validatedData);

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du produit: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Une erreur est survenue lors de la création du produit.');
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'sizes' => 'nullable|array',
                'colors' => 'nullable|array',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Gérer les images existantes
            $images = $product->images ?? [];

            // Gérer les nouvelles images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $images[] = $path;
                }
            }

            // Supprimer les images sélectionnées
            if ($request->has('delete_images')) {
                foreach ($request->delete_images as $index) {
                    if (isset($images[$index])) {
                        Storage::disk('public')->delete($images[$index]);
                        unset($images[$index]);
                    }
                }
                $images = array_values($images); // Réindexer le tableau
            }

            $validatedData['images'] = $images;
            $validatedData['slug'] = Str::slug($validatedData['name']);

            $product->update($validatedData);

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour du produit: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Une erreur est survenue lors de la mise à jour du produit.');
        }
    }

    public function updateActive(Request $request, Product $product)
    {
        try {
            $product->update([
                'is_active' => $request->boolean('is_active')
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue'], 500);
        }
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Supprimer les images
            if (!empty($product->images)) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            // Supprimer le produit
            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produit supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la suppression du produit: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la suppression du produit.');
        }
    }

    public function updateFeatured(Product $product)
    {
        $product->update([
            'is_featured' => !$product->is_featured
        ]);

        return response()->json([
            'success' => true,
            'is_featured' => $product->is_featured
        ]);
    }
}
