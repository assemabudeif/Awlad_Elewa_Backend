<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class GenerateDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:generate {--fresh : Drop all tables and recreate them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate comprehensive demo data for Awlad Elewa platform';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fresh = $this->option('fresh');

        $this->info('🎯 بدء توليد البيانات التجريبية لمنصة أولاد العلوى...');
        $this->newLine();

        if ($fresh) {
            if ($this->confirm('⚠️  هذا سيحذف جميع البيانات الموجودة. هل أنت متأكد؟')) {
                $this->info('🗑️  إعادة تعيين قاعدة البيانات...');
                Artisan::call('migrate:fresh');
                $this->info('✅ تم إعادة تعيين قاعدة البيانات');
            } else {
                $this->info('❌ تم إلغاء العملية');
                return 0;
            }
        }

        $this->info('📊 تشغيل السيدرز...');

        // تشغيل السيدرز
        $exitCode = Artisan::call('db:seed');

        if ($exitCode === 0) {
            $this->newLine();
            $this->info('🎉 تم إنشاء البيانات التجريبية بنجاح!');
            $this->newLine();

            $this->displaySummary();
        } else {
            $this->error('❌ حدث خطأ أثناء إنشاء البيانات التجريبية');
            return 1;
        }

        return 0;
    }

    private function displaySummary()
    {
        $this->line('📈 <comment>ملخص البيانات المُنشأة:</comment>');
        $this->line('');

        // عرض إحصائيات البيانات
        $stats = [
            ['المستخدمين', \App\Models\User::count(), '👤'],
            ['الفئات', \App\Models\Category::count(), '📂'],
            ['المنتجات', \App\Models\Product::count(), '📦'],
            ['البانرات', \App\Models\Banner::count(), '🖼️'],
            ['الطلبات', \App\Models\Order::count(), '🛒'],
            ['عناصر الطلبات', \App\Models\OrderItem::count(), '📋'],
            ['طلبات الإصلاح', \App\Models\RepairOrder::count(), '🔧'],
            ['الإشعارات', \App\Models\Notification::count(), '🔔'],
            ['الإعدادات', \App\Models\Setting::count(), '⚙️'],
        ];

        foreach ($stats as [$label, $count, $icon]) {
            $this->line(sprintf(
                '  %s <info>%s:</info> <comment>%d</comment>',
                $icon,
                $label,
                $count
            ));
        }

        $this->newLine();
        $this->line('🔐 <comment>بيانات الدخول:</comment>');
        $this->line('  📧 <info>الإيميل:</info> <comment>admin@awladelewa.com</comment>');
        $this->line('  🔑 <info>كلمة المرور:</info> <comment>password123</comment>');
        $this->newLine();

        $this->line('🌐 <comment>روابط مفيدة:</comment>');
        $this->line('  🏠 <info>الصفحة الرئيسية:</info> <comment>' . url('/') . '</comment>');
        $this->line('  👑 <info>لوحة الأدمن:</info> <comment>' . url('/admin') . '</comment>');
        $this->line('  📡 <info>API التوثيق:</info> <comment>' . url('/docs') . '</comment>');
        $this->newLine();

        $this->line('🎯 <comment>ملاحظات:</comment>');
        $this->line('  • تم ربط المنتجات بالصور الموجودة في storage/app/public/products');
        $this->line('  • تم إنشاء طلبات بحالات مختلفة للاختبار');
        $this->line('  • تم إنشاء إشعارات تجريبية لاختبار النظام');
        $this->line('  • جميع كلمات المرور للمستخدمين: password123');
    }
}
