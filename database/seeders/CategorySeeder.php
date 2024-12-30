<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'T-shirts',
                'description' => 'Collection de t-shirts tendance pour homme et femme',
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500',
                'is_active' => true,
            ],
            [
                'name' => 'Pantalons',
                'description' => 'Pantalons élégants et confortables',
                'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=500',
                'is_active' => true,
            ],
            [
                'name' => 'Robes',
                'description' => 'Robes pour toutes les occasions',
                'image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=500',
                'is_active' => true,
            ],
            [
                'name' => 'Vestes',
                'description' => 'Vestes et manteaux stylés',
                'image' => 'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?w=500',
                'is_active' => true,
            ],
            [
                'name' => 'Chaussures',
                'description' => 'Chaussures pour tous les styles',
                'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=500',
                'is_active' => true,
            ],
            [
                'name' => 'Accessoires',
                'description' => 'Accessoires pour compléter votre look',
                'image' => 'https://images.unsplash.com/photo-1611923134239-b9be5816e23d?w=500',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => $category['image'],
                'is_active' => $category['is_active'],
            ]);
        }
    }
}
