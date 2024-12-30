@extends('layouts.admin')

@section('title', 'Gestion des Produits')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des Produits</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Produit
        </a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des Produits</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th width="80">Image</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th width="100">Stock</th>
                            <th width="100">Statut</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td class="align-middle">
                                @if($product->images && is_array($product->images) && count($product->images) > 0)
                                    <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 100px;">
                                @elseif($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 100px;">
                                @else
                                    <span class="text-muted">Aucune image</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $product->name }}</div>
                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                            </td>
                            <td>{{ $product->category->name }}</td>
                            <td>
                                <div class="fw-bold">{{ number_format($product->price, 2) }} €</div>
                                @if($product->sale_price)
                                    <small class="text-danger">
                                        <del>{{ number_format($product->sale_price, 2) }} €</del>
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($product->quantity > 10)
                                    <span class="badge bg-success">{{ $product->quantity }}</span>
                                @elseif($product->quantity > 0)
                                    <span class="badge bg-warning">{{ $product->quantity }}</span>
                                @else
                                    <span class="badge bg-danger">Rupture</span>
                                @endif
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input toggle-status" 
                                           data-product-id="{{ $product->id }}"
                                           {{ $product->is_active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirmDelete(this.id)"
                                          id="delete-form-{{ $product->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialisation de DataTables
    $('.datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        order: [[1, 'asc']]
    });

    // Gestion du toggle de statut
    $('.toggle-status').change(function() {
        const productId = $(this).data('product-id');
        const isActive = $(this).prop('checked');

        $.ajax({
            url: `/admin/products/${productId}/active`,
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                is_active: isActive
            },
            success: function(response) {
                toastr.success('Statut mis à jour avec succès');
            },
            error: function(xhr) {
                toastr.error('Une erreur est survenue');
                $(this).prop('checked', !isActive);
            }
        });
    });
});
</script>
@endpush
