@extends('layouts.admin')

@section('title', 'Modifier un Produit')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Modifier le Produit</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
            
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product) }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="row g-4">
        @csrf
        @method('PUT')

        <!-- Informations de base -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations du Produit</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nom du Produit</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $product->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Catégorie</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id" 
                                    required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="price" class="form-label">Prix (€)</label>
                            <input type="number" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price', $product->price) }}" 
                                   step="0.01" 
                                   min="0" 
                                   required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="sale_price" class="form-label">Prix Promo (€)</label>
                            <input type="number" 
                                   class="form-control @error('sale_price') is-invalid @enderror" 
                                   id="sale_price" 
                                   name="sale_price" 
                                   value="{{ old('sale_price', $product->sale_price) }}" 
                                   step="0.01" 
                                   min="0">
                            @error('sale_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Stock</label>
                            <input type="number" 
                                   class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="{{ old('quantity', $product->quantity) }}" 
                                   min="0" 
                                   required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Images -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Images</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="images" class="form-label">Nouvelles Images</label>
                        <input type="file" 
                               class="form-control @error('images') is-invalid @enderror" 
                               id="images" 
                               name="images[]" 
                               multiple 
                               accept="image/*">
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(!empty($product->images))
                        <div class="row g-2">
                            @foreach($product->images as $image)
                                <div class="col-4">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         alt="Image produit" 
                                         class="img-thumbnail w-100"
                                         style="height: 100px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Options -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Options</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tailles Disponibles</label>
                        <div class="row g-2">
                            @php
                                $availableSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
                                $productSizes = is_array($product->sizes) ? $product->sizes : [];
                            @endphp
                            @foreach($availableSizes as $size)
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               id="size_{{ $size }}" 
                                               name="sizes[]" 
                                               value="{{ $size }}"
                                               {{ in_array($size, old('sizes', $productSizes)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="size_{{ $size }}">
                                            {{ $size }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('sizes')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Couleurs Disponibles</label>
                        <div class="row g-2">
                            @foreach(['Noir', 'Blanc', 'Rouge', 'Bleu', 'Vert', 'Jaune'] as $color)
                                <div class="col-4">
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               id="color_{{ $color }}" 
                                               name="colors[]" 
                                               value="{{ $color }}"
                                               {{ in_array($color, old('colors', $product->colors ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="color_{{ $color }}">
                                            {{ $color }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('colors')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="is_featured" 
                               name="is_featured" 
                               value="1"
                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            Produit en Vedette
                        </label>
                    </div>

                    <div class="form-check form-switch">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Produit Actif
                        </label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Enregistrer les Modifications
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Prévisualisation des images
    $('#images').change(function() {
        const files = $(this)[0].files;
        const maxSize = 2 * 1024 * 1024; // 2MB
        let valid = true;

        // Vérifier la taille des fichiers
        for (let i = 0; i < files.length; i++) {
            if (files[i].size > maxSize) {
                valid = false;
                toastr.error(`L'image ${files[i].name} est trop volumineuse. La taille maximum est de 2MB.`);
            }
        }

        if (!valid) {
            $(this).val('');
        }
    });
});
</script>
@endpush
