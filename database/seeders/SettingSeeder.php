<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'contact_email' => 'info@awlad-elewa.com',
            'contact_phone' => '+20 10 0000 0000',
            'facebook_url' => 'https://facebook.com/awlad-elewa',
            'whatsapp_url' => 'https://wa.me/201000000000',
            'instagram_url' => 'https://instagram.com/awlad-elewa',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
