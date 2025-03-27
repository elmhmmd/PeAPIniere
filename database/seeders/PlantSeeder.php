<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Plant;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    public function run()
    {
        $category = Category::create(['category_name' => 'Herbes aromatiques']);

        Plant::create([
            'name' => 'Basilic aromatique',
            'description' => 'Une plante aromatique parfaite pour la cuisine.',
            'price' => 5.99,
            'images' => json_encode(['https://example.com/basilic1.jpg']),
            'slug' => 'basilic-aromatique',
            'category_id' => $category->id,
        ]);

        Plant::create([
            'name' => 'Menthe fraîche',
            'description' => 'Idéale pour les boissons et desserts.',
            'price' => 4.50,
            'images' => json_encode(['https://example.com/menthe1.jpg']),
            'slug' => 'menthe-fraiche',
            'category_id' => $category->id,
        ]);
    }
}