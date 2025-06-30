<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'A Sleek And Modern Salon Chair Designed For Ultimate Comfort',
                'description' => 'The New Johnny Center Brazzi Improves Quality, Style, And Price, And Comes With A Warranty.',
                'price' => 4100,
                'category' => 'Chairs',
                'image' => 'chair1.png',
            ],
            [
                'name' => 'Hair Dryer 5000W Professional Blow Dryer With Diffuser',
                'description' => 'Professional hair dryer with diffuser for salon-quality results.',
                'price' => 800,
                'category' => 'Hair Tools',
                'image' => 'dryer1.png',
            ],
            [
                'name' => 'Wahl Professional 5-Star Cord/Cordless Magic Clip',
                'description' => 'Cordless trimmer for professional use.',
                'price' => 2500,
                'category' => 'Hair Trimmers',
                'image' => 'trimmer1.png',
            ],
        ];
        foreach ($products as $prod) {
            $category = Category::where('name', $prod['category'])->first();
            if ($category) {
                Product::firstOrCreate(
                    ['name' => $prod['name']],
                    [
                        'description' => $prod['description'],
                        'price' => $prod['price'],
                        'category_id' => $category->id,
                        'image' => $prod['image'],
                    ]
                );
            }
        }
    }
}
