<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'مدير النظام الرئيسي',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
        ]);

        $this->command->info('تم إنشاء المدير الرئيسي بنجاح!');
        $this->command->info('البريد الإلكتروني: admin@example.com');
        $this->command->info('كلمة المرور: 12345678');
        $this->command->info('ملاحظة: هذا المدير هو المدير الرئيسي وله صلاحيات كاملة لإدارة النظام');
    }
}
