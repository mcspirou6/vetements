<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'T-shirt Basique Noir',
                'slug' => 't-shirt-basique-noir',
                'description' => 'T-shirt basique en coton de haute qualité, parfait pour un look décontracté.',
                'price' => 19.99,
                'category_id' => 1,
                'stock' => 100,
                'image' => 'products/tshirt-noir.jpg'
            ],
            [
                'name' => 'Jean Slim Bleu',
                'slug' => 'jean-slim-bleu',
                'description' => 'Jean slim en denim stretch confortable, coupe moderne.',
                'price' => 49.99,
                'category_id' => 2,
                'stock' => 75,
                'image' => 'products/jean-bleu.jpg'
            ],
            [
                'name' => 'Robe d\'été Fleurie',
                'slug' => 'robe-ete-fleurie',
                'description' => 'Robe légère à motifs floraux, parfaite pour l\'été.',
                'price' => 39.99,
                'category_id' => 3,
                'stock' => 50,
                'image' => 'products/robe-fleurie.jpg'
            ],
            [
                'name' => 'Chemise Business Blanche',
                'slug' => 'chemise-business-blanche',
                'description' => 'Chemise classique en coton, coupe régulière.',
                'price' => 29.99,
                'category_id' => 1,
                'stock' => 85,
                'image' => 'products/chemise-blanche.jpg'
            ],
            [
                'name' => 'Veste en Cuir Noir',
                'slug' => 'veste-cuir-noir',
                'description' => 'Veste en cuir véritable, style motard.',
                'price' => 199.99,
                'category_id' => 4,
                'stock' => 25,
                'image' => 'products/veste-cuir.jpg'
            ],
            [
                'name' => 'Pull-over Gris',
                'slug' => 'pull-over-gris',
                'description' => 'Pull-over chaud en laine mélangée.',
                'price' => 45.99,
                'category_id' => 4,
                'stock' => 60,
                'image' => 'products/pull-gris.jpg'
            ],
            [
                'name' => 'Short en Jean',
                'slug' => 'short-jean',
                'description' => 'Short en jean décontracté pour l\'été.',
                'price' => 34.99,
                'category_id' => 2,
                'stock' => 70,
                'image' => 'products/short-jean.jpg'
            ],
            [
                'name' => 'Robe de Soirée Noire',
                'slug' => 'robe-soiree-noire',
                'description' => 'Robe élégante pour les occasions spéciales.',
                'price' => 89.99,
                'category_id' => 3,
                'stock' => 40,
                'image' => 'products/robe-noire.jpg'
            ],
            [
                'name' => 'Blazer Bleu Marine',
                'slug' => 'blazer-bleu-marine',
                'description' => 'Blazer classique pour un look professionnel.',
                'price' => 129.99,
                'category_id' => 4,
                'stock' => 35,
                'image' => 'products/blazer-bleu.jpg'
            ],
            [
                'name' => 'T-shirt Imprimé',
                'slug' => 't-shirt-imprime',
                'description' => 'T-shirt avec design graphique original.',
                'price' => 24.99,
                'category_id' => 1,
                'stock' => 90,
                'image' => 'products/tshirt-imprime.jpg'
            ],
            [
                'name' => 'Pantalon Chino Beige',
                'slug' => 'pantalon-chino-beige',
                'description' => 'Pantalon chino confortable en coton.',
                'price' => 44.99,
                'category_id' => 2,
                'stock' => 65,
                'image' => 'products/chino-beige.jpg'
            ],
            [
                'name' => 'Robe Midi Plissée',
                'slug' => 'robe-midi-plissee',
                'description' => 'Robe midi élégante avec jupe plissée.',
                'price' => 69.99,
                'category_id' => 3,
                'stock' => 45,
                'image' => 'products/robe-plissee.jpg'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
