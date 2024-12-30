@extends('layouts.app')

@section('title', 'Paramètres du compte')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Menu latéral -->
        @include('account.partials.sidebar')

        <!-- Contenu principal -->
        <div class="flex-1">
            <!-- Informations personnelles -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-medium">Informations personnelles</h2>
                </div>

                <form action="{{ route('account.settings.update-profile') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Avatar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-4">
                                Photo de profil
                            </label>
                            <div class="flex items-center">
                                <img class="w-20 h-20 rounded-full" 
                                     src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                                     alt="{{ auth()->user()->name }}">
                                <div class="ml-4">
                                    <div class="flex items-center gap-4">
                                        <label class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                                            <input type="file" name="avatar" class="sr-only" accept="image/*">
                                            Changer
                                        </label>
                                        @if(auth()->user()->avatar_url)
                                            <button type="button" 
                                                    onclick="document.getElementById('remove-avatar-form').submit()"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-red-600 hover:text-red-500">
                                                Supprimer
                                            </button>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">
                                        JPG, PNG ou GIF. 1 MB maximum.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Prénom et Nom -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="firstname" class="block text-sm font-medium text-gray-700 mb-2">
                                    Prénom
                                </label>
                                <input type="text" name="firstname" id="firstname" 
                                       value="{{ old('firstname', auth()->user()->firstname) }}"
                                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900 @error('firstname') border-red-500 @enderror">
                                @error('firstname')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="lastname" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom
                                </label>
                                <input type="text" name="lastname" id="lastname" 
                                       value="{{ old('lastname', auth()->user()->lastname) }}"
                                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900 @error('lastname') border-red-500 @enderror">
                                @error('lastname')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', auth()->user()->email) }}"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Téléphone
                            </label>
                            <input type="tel" name="phone" id="phone" 
                                   value="{{ old('phone', auth()->user()->phone) }}"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date de naissance -->
                        <div>
                            <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-2">
                                Date de naissance
                            </label>
                            <input type="date" name="birthdate" id="birthdate" 
                                   value="{{ old('birthdate', auth()->user()->birthdate?->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900 @error('birthdate') border-red-500 @enderror">
                            @error('birthdate')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit"
                                    class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-lg text-white bg-gray-900 hover:bg-gray-800 focus:outline-none">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Mot de passe -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-medium">Changer le mot de passe</h2>
                </div>

                <form action="{{ route('account.settings.update-password') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Mot de passe actuel
                            </label>
                            <input type="password" name="current_password" id="current_password"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900 @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Nouveau mot de passe
                            </label>
                            <input type="password" name="password" id="password"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmer le nouveau mot de passe
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                        </div>

                        <div>
                            <button type="submit"
                                    class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-lg text-white bg-gray-900 hover:bg-gray-800 focus:outline-none">
                                Changer le mot de passe
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Préférences -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-medium">Préférences de notification</h2>
                </div>

                <form action="{{ route('account.settings.update-preferences') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="newsletter" value="1"
                                       {{ auth()->user()->newsletter ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                <span class="ml-2">
                                    <span class="text-sm font-medium text-gray-700">Newsletter</span>
                                    <p class="text-sm text-gray-500">
                                        Recevez nos dernières nouveautés et offres exclusives
                                    </p>
                                </span>
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="order_updates" value="1"
                                       {{ auth()->user()->order_updates ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                <span class="ml-2">
                                    <span class="text-sm font-medium text-gray-700">Mises à jour des commandes</span>
                                    <p class="text-sm text-gray-500">
                                        Recevez des notifications sur l'état de vos commandes
                                    </p>
                                </span>
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="promotional_emails" value="1"
                                       {{ auth()->user()->promotional_emails ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                <span class="ml-2">
                                    <span class="text-sm font-medium text-gray-700">Emails promotionnels</span>
                                    <p class="text-sm text-gray-500">
                                        Recevez des offres spéciales et des réductions exclusives
                                    </p>
                                </span>
                            </label>
                        </div>

                        <div>
                            <button type="submit"
                                    class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-lg text-white bg-gray-900 hover:bg-gray-800 focus:outline-none">
                                Enregistrer les préférences
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Suppression du compte -->
            <div class="mt-8">
                <button onclick="document.getElementById('delete-account-modal').classList.remove('hidden')"
                        class="text-sm text-red-600 hover:text-red-500">
                    Supprimer mon compte
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression de compte -->
<div id="delete-account-modal" 
     class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden"
     x-show="showDeleteModal"
     x-transition>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Supprimer votre compte ?
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    Cette action est irréversible. Toutes vos données seront définitivement supprimées.
                </p>

                <form action="{{ route('account.settings.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmez votre mot de passe
                        </label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="button"
                                onclick="document.getElementById('delete-account-modal').classList.add('hidden')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                            Annuler
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-500">
                            Supprimer mon compte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Formulaire de suppression d'avatar -->
<form id="remove-avatar-form" action="{{ route('account.settings.remove-avatar') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection
