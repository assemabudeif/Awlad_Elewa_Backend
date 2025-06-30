<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Chairs', 'icon' => 'chair-icon.png'],
            ['name' => 'Washing Chair', 'icon' => 'washing-chair-icon.png'],
            ['name' => 'Spare Parts', 'icon' => 'spare-parts-icon.png'],
            ['name' => 'Hair Trimmers', 'icon' => 'trimmer-icon.png'],
            ['name' => 'Hair Tools', 'icon' => 'tools-icon.png'],
        ];
        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
