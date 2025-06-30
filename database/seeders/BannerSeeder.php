<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            ['image' => 'banner1.png', 'link' => 'https://awlad-elewa.com/shop'],
            ['image' => 'banner2.png', 'link' => 'https://awlad-elewa.com/offers'],
        ];
        foreach ($banners as $banner) {
            Banner::firstOrCreate(['image' => $banner['image']], $banner);
        }
    }
}
