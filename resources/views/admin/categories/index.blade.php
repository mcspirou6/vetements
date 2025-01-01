@extends('layouts.admin')

@section('title', 'Gestion des catégories')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des catégories</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvelle catégorie
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des catégories</h5>
            <div class="input-group" style="width: 300px;">
                <input type="text" class="form-control" placeholder="Rechercher une catégorie...">
                <button class="btn btn-outline-primary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Nombre de produits</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td class="align-middle" style="width: 100px;">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                         alt="{{ $category->name }}" 
                                         class="img-thumbnail"
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-folder fa-2x text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="fw-bold">{{ $category->name }}</div>
                                <small class="text-muted">{{ $category->slug }}</small>
                            </td>
                            <td class="align-middle">
                                {{ Str::limit($category->description, 100) }}
                            </td>
                            <td class="align-middle text-center">
                                <span class="badge bg-info">
                                    {{ $category->products_count }} produit(s)
                                </span>
                            </td>
                            <td class="align-middle">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input toggle-status" 
                                           data-category-id="{{ $category->id }}"
                                           {{ $category->active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="btn-group">
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="btn btn-sm btn-primary"
                                       data-bs-toggle="tooltip"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('shop.category', $category) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="Voir en boutique"
                                       target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip"
                                                title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
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

<style>
/* Personnalisation des boutons pour correspondre à la boutique */
.btn-primary {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-primary:hover {
    background-color: #bb2d3b;
    border-color: #bb2d3b;
}

.btn-outline-primary {
    color: #dc3545;
    border-color: #dc3545;
}

.btn-outline-primary:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
}

.form-check-input:checked {
    background-color: #dc3545;
    border-color: #dc3545;
}

/* Style des badges */
.badge.bg-info {
    background-color: #dc3545 !important;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialisation des tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Initialisation de DataTables
    $('.datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        order: [[1, 'asc']]
    });

    // Gestion du toggle de statut
    $('.toggle-status').change(function() {
        const categoryId = $(this).data('category-id');
        const isActive = $(this).prop('checked');

        $.ajax({
            url: `/admin/categories/${categoryId}/toggle-status`,
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                active: isActive
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
@endsection
