@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Modifier le produit</h1>
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du produit</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Catégorie</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
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

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="price" class="form-label">Prix</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                <span class="input-group-text">€</span>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="sale_price" class="form-label">Prix promotionnel</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" 
                                       id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
                                <span class="input-group-text">€</span>
                            </div>
                            @error('sale_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantité en stock</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sizes" class="form-label">Tailles disponibles</label>
                            <input type="text" class="form-control @error('sizes') is-invalid @enderror" 
                                   id="sizes" name="sizes" 
                                   value="{{ old('sizes', is_array($product->sizes) ? implode(',', $product->sizes) : '') }}"
                                   placeholder="XS,S,M,L,XL">
                            <div class="form-text">Séparez les tailles par des virgules</div>
                            @error('sizes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="colors" class="form-label">Couleurs disponibles</label>
                            <input type="text" class="form-control @error('colors') is-invalid @enderror" 
                                   id="colors" name="colors" 
                                   value="{{ old('colors', is_array($product->colors) ? implode(',', $product->colors) : '') }}"
                                   placeholder="Rouge,Bleu,Vert">
                            <div class="form-text">Séparez les couleurs par des virgules</div>
                            @error('colors')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label d-block">Image principale</label>
                            @if($product->main_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $product->main_image) }}" 
                                         alt="Image principale actuelle"
                                         class="img-thumbnail" style="max-height: 150px; border-radius: 10px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('main_image') is-invalid @enderror" 
                                   id="main_image" name="main_image" accept="image/*">
                            @error('main_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Images secondaires</label>
                            @if(is_array($product->images) && count($product->images) > 0)
                                <div class="row mb-2">
                                    @foreach($product->images as $index => $image)
                                        <div class="col-auto">
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $image) }}" 
                                                     alt="Image {{ $index + 1 }}"
                                                     class="img-thumbnail" style="max-height: 100px;">
                                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                                        onclick="removeImage({{ $index }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" class="form-control @error('images') is-invalid @enderror" 
                                   id="images" name="images[]" accept="image/*" multiple>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" 
                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} value="1">
                            <label class="form-check-label" for="is_featured">Produit en vedette</label>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }} value="1">
                            <label class="form-check-label" for="is_active">Produit actif</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function removeImage(index) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'remove_images[]';
    input.value = index;
    document.querySelector('form').appendChild(input);
    
    const imageContainer = event.target.closest('.col-auto');
    imageContainer.remove();
}
</script>
@endpush
@endsection
