<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::first() ?? Category::create([
            'name' => 'Général',
            'slug' => 'general',
        ]);

        $products = [
            [
                'name' => 'Produit 1',
                'price' => 39.00,
                'stock' => 50,
                'description' => 'Premier produit de test.',
            ],
            [
                'name' => 'Produit 2',
                'price' => 78.00,
                'stock' => 30,
                'description' => 'Deuxième produit de test.',
            ],
            [
                'name' => 'Produit 3',
                'price' => 19.90,
                'stock' => 100,
                'description' => 'Troisième produit de test.',
            ],
        ];

        foreach ($products as $data) {
            Product::create([
                'category_id' => $category->id,
                'name'        => $data['name'],
                'slug'        => Str::slug($data['name']) . '-' . uniqid(),
                'description' => $data['description'],
                'price'       => $data['price'],
                'stock'       => $data['stock'],
                'image_path'  => null,
                'is_active'   => true,
            ]);
        }
    }
}
