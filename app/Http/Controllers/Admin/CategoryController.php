<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::where('parent_id', null)->get();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('categories', 'public');
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'image' => $image,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès');
    }

    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)
            ->where('parent_id', null)
            ->get();
            
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $image = $category->image;
        if ($request->hasFile('image')) {
            if ($image) {
                Storage::disk('public')->delete($image);
            }
            $image = $request->file('image')->store('categories', 'public');
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'image' => $image,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès');
    }

    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // Déplacer les produits de cette catégorie vers la catégorie parente
        if ($category->parent_id) {
            $category->products()->update(['category_id' => $category->parent_id]);
        }

        // Déplacer les sous-catégories vers la catégorie parente
        $category->children()->update(['parent_id' => $category->parent_id]);

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès');
    }

    public function updateActive(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $category->is_active
        ]);
    }
}
