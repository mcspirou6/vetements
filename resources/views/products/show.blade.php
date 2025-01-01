@extends('layouts.app')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category', $product->category) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Galerie d'images -->
        <div class="col-md-6">
            <div class="position-relative mb-4" style="height: 460px;">
                <img class="w-100 h-100 object-fit-contain" 
                     src="{{ asset('storage/' . (is_array($product->images) && count($product->images) > 0 ? $product->images[0] : 'placeholder.jpg')) }}" 
                     alt="{{ $product->name }}"
                     id="main-image">
            </div>
            @if(is_array($product->images) && count($product->images) > 1)
                <div class="row g-2">
                    @foreach($product->images as $index => $image)
                        <div class="col-3">
                            <button onclick="setMainImage('{{ asset('storage/' . $image) }}')"
                                    class="btn p-0 w-100 h-100 border rounded">
                                <img class="w-100 h-100 object-fit-cover" 
                                     src="{{ asset('storage/' . $image) }}" 
                                     alt="{{ $product->name }} - Image {{ $index + 1 }}">
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Informations produit -->
        <div class="col-md-6">
            <h1 class="h2 mb-3">{{ $product->name }}</h1>
            
            <div class="mb-4">
                @if($product->sale_price)
                    <span class="h3 text-danger me-2">{{ number_format($product->sale_price, 2, ',', ' ') }} €</span>
                    <span class="h5 text-muted text-decoration-line-through">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                    <span class="badge bg-danger ms-2">
                        -{{ number_format((($product->price - $product->sale_price) / $product->price) * 100, 0) }}%
                    </span>
                @else
                    <span class="h3">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                @endif
            </div>

            <p class="lead mb-4">{{ $product->description }}</p>

            <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                @csrf
                <div class="row g-3">
                    @if(is_array($product->sizes) && count($product->sizes) > 0)
                        <div class="col-md-6">
                            <label for="size" class="form-label">Taille</label>
                            <select name="size" id="size" class="form-select" required>
                                <option value="">Choisir une taille</option>
                                @foreach($product->sizes as $size)
                                    <option value="{{ $size }}">{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if(is_array($product->colors) && count($product->colors) > 0)
                        <div class="col-md-6">
                            <label for="color" class="form-label">Couleur</label>
                            <select name="color" id="color" class="form-select" required>
                                <option value="">Choisir une couleur</option>
                                @foreach($product->colors as $color)
                                    <option value="{{ $color }}">{{ $color }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-md-6">
                        <label for="quantity" class="form-label">Quantité</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" 
                               value="1" min="1" max="{{ $product->quantity }}" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-danger btn-lg w-100 mt-4">
                    <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function setMainImage(imageUrl) {
    document.getElementById('main-image').src = imageUrl;
}
</script>
@endpush
@endsection
