@extends('layouts.app')

@section('title', 'Mes adresses')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Menu latéral -->
        @include('account.partials.sidebar')

        <!-- Contenu principal -->
        <div class="flex-1">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b flex justify-between items-center">
                    <h1 class="text-lg font-medium">Mes adresses</h1>
                    <button onclick="document.getElementById('add-address-modal').classList.remove('hidden')"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-white bg-gray-900 hover:bg-gray-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter une adresse
                    </button>
                </div>

                @if($addresses->isEmpty())
                    <div class="p-6 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-medium mb-2">Aucune adresse</h2>
                        <p class="text-gray-500 mb-4">Ajoutez votre première adresse de livraison</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                        @foreach($addresses as $address)
                            <div class="border rounded-lg p-6 relative">
                                @if($address->is_default)
                                    <span class="absolute top-4 right-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Par défaut
                                    </span>
                                @endif

                                <div class="mb-4">
                                    <h3 class="font-medium">{{ $address->name }}</h3>
                                    <address class="text-gray-600 not-italic mt-1">
                                        {{ $address->address }}<br>
                                        @if($address->address_complement)
                                            {{ $address->address_complement }}<br>
                                        @endif
                                        {{ $address->postal_code }} {{ $address->city }}<br>
                                        {{ $address->country_name }}
                                    </address>
                                </div>

                                <div class="flex items-center gap-4">
                                    @unless($address->is_default)
                                        <form action="{{ route('account.addresses.set-default', $address) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                                Définir par défaut
                                            </button>
                                        </form>
                                    @endunless

                                    <button onclick="editAddress('{{ $address->id }}')"
                                            class="text-sm text-gray-600 hover:text-gray-900">
                                        Modifier
                                    </button>

                                    @unless($address->is_default)
                                        <form action="{{ route('account.addresses.destroy', $address) }}" 
                                              method="POST"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette adresse ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-500">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endunless
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal d'ajout/modification d'adresse -->
<div id="add-address-modal" 
     class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden"
     x-show="showAddressModal"
     x-transition>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium mb-4" id="address-modal-title">
                    Ajouter une adresse
                </h3>

                <form action="{{ route('account.addresses.store') }}" method="POST" id="address-form">
                    @csrf
                    <div id="method-field"></div>

                    <div class="space-y-6">
                        <!-- Nom de l'adresse -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom de l'adresse
                            </label>
                            <input type="text" name="name" id="name" required
                                   placeholder="Ex: Domicile, Bureau..."
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                        </div>

                        <!-- Adresse -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Adresse
                            </label>
                            <input type="text" name="address" id="address" required
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                        </div>

                        <!-- Complément d'adresse -->
                        <div>
                            <label for="address_complement" class="block text-sm font-medium text-gray-700 mb-2">
                                Complément d'adresse (optionnel)
                            </label>
                            <input type="text" name="address_complement" id="address_complement"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                        </div>

                        <!-- Code postal et Ville -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    Code postal
                                </label>
                                <input type="text" name="postal_code" id="postal_code" required
                                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ville
                                </label>
                                <input type="text" name="city" id="city" required
                                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                            </div>
                        </div>

                        <!-- Pays -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                Pays
                            </label>
                            <select name="country" id="country" required
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                                <option value="FR">France</option>
                                <option value="BE">Belgique</option>
                                <option value="CH">Suisse</option>
                                <option value="LU">Luxembourg</option>
                            </select>
                        </div>

                        <!-- Par défaut -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_default" value="1"
                                       class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                <span class="ml-2 text-sm text-gray-600">
                                    Définir comme adresse par défaut
                                </span>
                            </label>
                        </div>

                        <div class="flex justify-end gap-4">
                            <button type="button"
                                    onclick="document.getElementById('add-address-modal').classList.add('hidden')"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800">
                                Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editAddress(id) {
    fetch(`/account/addresses/${id}/edit`)
        .then(response => response.json())
        .then(address => {
            const form = document.getElementById('address-form');
            const title = document.getElementById('address-modal-title');
            const methodField = document.getElementById('method-field');

            // Mise à jour du titre
            title.textContent = 'Modifier l\'adresse';

            // Mise à jour de l'action du formulaire
            form.action = `/account/addresses/${id}`;

            // Ajout de la méthode PUT
            methodField.innerHTML = '@method("PUT")';

            // Remplissage des champs
            form.querySelector('#name').value = address.name;
            form.querySelector('#address').value = address.address;
            form.querySelector('#address_complement').value = address.address_complement || '';
            form.querySelector('#postal_code').value = address.postal_code;
            form.querySelector('#city').value = address.city;
            form.querySelector('#country').value = address.country;
            form.querySelector('input[name="is_default"]').checked = address.is_default;

            // Affichage du modal
            document.getElementById('add-address-modal').classList.remove('hidden');
        });
}
</script>
@endpush
