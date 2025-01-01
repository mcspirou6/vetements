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
        $products = Product::with('category')
            ->latest()
            ->paginate(12);
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

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'sizes' => 'nullable|string',
                'colors' => 'nullable|string',
                'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_featured' => 'boolean',
                'is_active' => 'boolean',
            ]);

            // Traitement des tailles et couleurs
            $validated['sizes'] = $request->sizes ? explode(',', $request->sizes) : [];
            $validated['colors'] = $request->colors ? explode(',', $request->colors) : [];

            // Gestion de l'image principale
            $validated['main_image'] = $request->file('main_image')->store('products', 'public');

            // Gestion des images secondaires
            $images = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $images[] = $image->store('products', 'public');
                }
            }
            $validated['images'] = $images;

            $validated['slug'] = Str::slug($validated['name']);

            $product = Product::create($validated);

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
        if (is_string($product->colors)) {
            $product->colors = json_decode($product->colors, true);
        }
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'sizes' => 'nullable|string',
                'colors' => 'nullable|string',
                'main_image' => 'nullable|image|max:2048',
                'images.*' => 'nullable|image|max:2048',
                'is_featured' => 'nullable|boolean',
                'is_active' => 'nullable|boolean',
            ]);

            // Traitement des tailles et couleurs
            $validated['sizes'] = $request->sizes ? explode(',', $request->sizes) : [];
            $validated['colors'] = $request->colors ? explode(',', $request->colors) : [];
            
            // Gestion des booléens
            $validated['is_featured'] = $request->has('is_featured');
            $validated['is_active'] = $request->has('is_active');

            // Gestion de l'image principale
            if ($request->hasFile('main_image')) {
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }
                $validated['main_image'] = $request->file('main_image')->store('products', 'public');
            }

            // Gestion des images secondaires
            $images = $product->images ?? [];
            
            // Supprimer les images marquées pour suppression
            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $index) {
                    if (isset($images[$index])) {
                        Storage::disk('public')->delete($images[$index]);
                        unset($images[$index]);
                    }
                }
                $images = array_values($images); // Réindexer le tableau
            }

            // Ajouter les nouvelles images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $images[] = $image->store('products', 'public');
                }
            }
            
            $validated['images'] = $images;
            $validated['slug'] = Str::slug($validated['name']);

            // Mise à jour du produit
            $product->update($validated);

            DB::commit();
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Le produit a été mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de la mise à jour du produit: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du produit: ' . $e->getMessage());
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
