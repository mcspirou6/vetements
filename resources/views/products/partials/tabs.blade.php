<div x-data="{ activeTab: 'description' }" class="mt-16">
    <!-- Onglets -->
    <div class="border-b">
        <nav class="-mb-px flex space-x-8">
            <button @click="activeTab = 'description'"
                    :class="{ 'border-gray-900 text-gray-900': activeTab === 'description' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Description
            </button>
            <button @click="activeTab = 'details'"
                    :class="{ 'border-gray-900 text-gray-900': activeTab === 'details' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Détails
            </button>
            <button @click="activeTab = 'reviews'"
                    :class="{ 'border-gray-900 text-gray-900': activeTab === 'reviews' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Avis ({{ $product->reviews_count }})
            </button>
        </nav>
    </div>

    <!-- Contenu des onglets -->
    <div class="py-8">
        <!-- Description -->
        <div x-show="activeTab === 'description'" x-transition>
            <div class="prose max-w-none">
                {!! $product->long_description !!}
            </div>
        </div>

        <!-- Détails -->
        <div x-show="activeTab === 'details'" x-transition>
            <table class="w-full">
                <tbody class="divide-y">
                    <tr>
                        <th class="py-4 text-sm font-medium text-gray-900 text-left w-1/4">Référence</th>
                        <td class="py-4 text-sm text-gray-500">{{ $product->sku }}</td>
                    </tr>
                    <tr>
                        <th class="py-4 text-sm font-medium text-gray-900 text-left w-1/4">Catégorie</th>
                        <td class="py-4 text-sm text-gray-500">{{ $product->category->name }}</td>
                    </tr>
                    <tr>
                        <th class="py-4 text-sm font-medium text-gray-900 text-left w-1/4">Tailles disponibles</th>
                        <td class="py-4 text-sm text-gray-500">
                            {{ $product->sizes->pluck('name')->join(', ') }}
                        </td>
                    </tr>
                    <tr>
                        <th class="py-4 text-sm font-medium text-gray-900 text-left w-1/4">Couleurs disponibles</th>
                        <td class="py-4 text-sm text-gray-500">
                            {{ $product->colors->pluck('name')->join(', ') }}
                        </td>
                    </tr>
                    <tr>
                        <th class="py-4 text-sm font-medium text-gray-900 text-left w-1/4">Matériaux</th>
                        <td class="py-4 text-sm text-gray-500">{{ $product->materials }}</td>
                    </tr>
                    <tr>
                        <th class="py-4 text-sm font-medium text-gray-900 text-left w-1/4">Entretien</th>
                        <td class="py-4 text-sm text-gray-500">{{ $product->care_instructions }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Avis -->
        <div x-show="activeTab === 'reviews'" x-transition>
            <!-- Résumé des avis -->
            <div class="flex items-center mb-8">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-gray-600 ml-2">
                            {{ number_format($product->average_rating, 1) }} sur 5 ({{ $product->reviews_count }} avis)
                        </span>
                    </div>
                    <!-- Distribution des notes -->
                    @foreach(range(5, 1) as $rating)
                        <div class="flex items-center mb-1">
                            <span class="text-sm text-gray-600 w-8">{{ $rating }}★</span>
                            <div class="flex-1 h-4 mx-2 bg-gray-200 rounded-full">
                                <div class="h-4 bg-yellow-400 rounded-full"
                                     style="width: {{ ($product->reviews->where('rating', $rating)->count() / max(1, $product->reviews_count)) * 100 }}%">
                                </div>
                            </div>
                            <span class="text-sm text-gray-600 w-12">
                                {{ $product->reviews->where('rating', $rating)->count() }}
                            </span>
                        </div>
                    @endforeach
                </div>
                @auth
                    @unless($product->reviews->contains('user_id', auth()->id()))
                        <div class="ml-8">
                            <button onclick="openReviewModal()"
                                    class="bg-gray-900 text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-colors">
                                Donner mon avis
                            </button>
                        </div>
                    @endunless
                @endauth
            </div>

            <!-- Liste des avis -->
            <div class="space-y-8">
                @forelse($product->reviews()->with('user')->latest()->get() as $review)
                    <div class="border-b pb-8">
                        <div class="flex items-start">
                            <img class="w-10 h-10 rounded-full" 
                                 src="{{ $review->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) }}" 
                                 alt="{{ $review->user->name }}">
                            <div class="ml-4">
                                <div class="flex items-center">
                                    <h4 class="font-medium">{{ $review->user->name }}</h4>
                                    <span class="mx-2">•</span>
                                    <time class="text-gray-500 text-sm">
                                        {{ $review->created_at->format('d/m/Y') }}
                                    </time>
                                </div>
                                <div class="flex items-center mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                                @if($review->title)
                                    <h5 class="font-medium mt-2">{{ $review->title }}</h5>
                                @endif
                                <p class="text-gray-600 mt-2">{{ $review->content }}</p>
                                @if($review->images->isNotEmpty())
                                    <div class="flex gap-2 mt-4">
                                        @foreach($review->images as $image)
                                            <img src="{{ $image->url }}" 
                                                 alt="Image de l'avis" 
                                                 class="w-20 h-20 object-cover rounded-lg cursor-pointer"
                                                 onclick="openImageModal('{{ $image->url }}')">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">
                        Aucun avis pour le moment. Soyez le premier à donner votre avis !
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal d'ajout d'avis -->
<div id="review-modal" 
     class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden"
     x-show="showReviewModal"
     x-transition>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <h3 class="text-lg font-medium mb-4">Donner mon avis</h3>
                <form action="{{ route('products.reviews.store', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Note -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Note</label>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" name="rating" value="{{ $i }}" class="sr-only peer" id="rating-{{ $i }}" required>
                                <label for="rating-{{ $i }}" class="cursor-pointer p-1">
                                    <svg class="w-8 h-8 peer-checked:text-yellow-400 text-gray-300"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Titre -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                            Titre (optionnel)
                        </label>
                        <input type="text" name="title" id="title"
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900">
                    </div>

                    <!-- Commentaire -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
                            Votre avis
                        </label>
                        <textarea name="content" id="content" rows="4" required
                                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-gray-900"></textarea>
                    </div>

                    <!-- Images -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Photos (optionnel)
                        </label>
                        <input type="file" name="images[]" multiple accept="image/*"
                               class="w-full">
                        <p class="text-sm text-gray-500 mt-1">
                            Vous pouvez ajouter jusqu'à 3 photos
                        </p>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="button" onclick="closeReviewModal()"
                                class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                            Annuler
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">
                            Publier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'image -->
<div id="image-modal" 
     class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden"
     onclick="this.classList.add('hidden')">
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <img src="" alt="Image en grand" class="max-w-full max-h-full">
    </div>
</div>

@push('scripts')
<script>
    function openReviewModal() {
        document.getElementById('review-modal').classList.remove('hidden');
    }

    function closeReviewModal() {
        document.getElementById('review-modal').classList.add('hidden');
    }

    function openImageModal(url) {
        const modal = document.getElementById('image-modal');
        modal.querySelector('img').src = url;
        modal.classList.remove('hidden');
    }
</script>
@endpush
