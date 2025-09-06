<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{User, Category, Product, ProductImage, Banner, Order, OrderItem, RepairOrder, Notification, Setting};
use Illuminate\Support\Facades\{Hash, Schema, DB};
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 بدء إنشاء البيانات التجريبية...');

        // إزالة القيد unique مؤقتاً للسماح بإنشاء بيانات تجريبية
        $this->removeUniqueConstraint();

        // إنشاء المستخدمين
        $users = $this->createUsers();
        $this->command->info('✅ تم إنشاء المستخدمين');

        // إنشاء الفئات
        $categories = $this->createCategories();
        $this->command->info('✅ تم إنشاء الفئات');

        // إنشاء المنتجات
        $products = $this->createProducts($categories);
        $this->command->info('✅ تم إنشاء المنتجات');

        // إنشاء البانرات
        $this->createBanners();
        $this->command->info('✅ تم إنشاء البانرات');

        // إنشاء الطلبات
        $this->createOrders($users, $products);
        $this->command->info('✅ تم إنشاء الطلبات');

        // إنشاء طلبات الإصلاح
        $this->createRepairOrders($users);
        $this->command->info('✅ تم إنشاء طلبات الإصلاح');

        // إنشاء الإشعارات
        $this->createNotifications($users);
        $this->command->info('✅ تم إنشاء الإشعارات');

        // إنشاء الإعدادات
        $this->createSettings();
        $this->command->info('✅ تم إنشاء الإعدادات');

        // إعادة إنشاء القيد unique
        $this->addUniqueConstraint();

        $this->command->info('🎉 تم إنشاء جميع البيانات التجريبية بنجاح!');
    }

    private function createUsers()
    {
        $users = collect();

        // إنشاء أو الحصول على الأدوار
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $customerRole = Role::firstOrCreate(['name' => 'Customer']);

        // المدير الرئيسي
        $admin = User::create([
            'name' => 'أحمد العلوى',
            'email' => 'admin@awladelewa.com',
            'password' => Hash::make('password123'),
            'phone1' => '01234567890',
            'phone2' => null,
            'fcm_token' => 'admin_token_' . Str::random(32),
            'notifications_enabled' => true,
            'email_verified_at' => now(),
        ]);

        // إعطاء role المدير للمستخدم
        $admin->assignRole('Admin');
        $users->push($admin);

        // عملاء تجريبيين
        $customers = [
            ['name' => 'محمد أحمد', 'email' => 'mohamed@example.com', 'phone1' => '01112223334'],
            ['name' => 'فاطمة محمود', 'email' => 'fatma@example.com', 'phone1' => '01223334445'],
            ['name' => 'علي حسن', 'email' => 'ali@example.com', 'phone1' => '01334445556'],
            ['name' => 'نور الدين', 'email' => 'nour@example.com', 'phone1' => '01445556667'],
            ['name' => 'سارة عبدالله', 'email' => 'sara@example.com', 'phone1' => '01556667778'],
            ['name' => 'خالد محمد', 'email' => 'khaled@example.com', 'phone1' => '01667778889'],
            ['name' => 'مريم سالم', 'email' => 'mariam@example.com', 'phone1' => '01778889990'],
            ['name' => 'عمر فاروق', 'email' => 'omar@example.com', 'phone1' => '01889990001'],
        ];

        foreach ($customers as $customer) {
            $user = User::create([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'password' => Hash::make('password123'),
                'phone1' => $customer['phone1'],
                'phone2' => rand(0, 1) ? '01' . rand(100000000, 999999999) : null,
                'fcm_token' => 'user_token_' . Str::random(32),
                'notifications_enabled' => rand(0, 1),
                'email_verified_at' => now(),
            ]);

            // إعطاء role العميل للمستخدم
            $user->assignRole('Customer');
            $users->push($user);
        }

        return $users;
    }

    private function createCategories()
    {
        $categoryImages = [
            'categories/79349576452b5964523825494840dba99f3a7540.png',
            'categories/807698b9432e609cfa5b25ca651feac56a84eed8.jpg',
            'categories/84ac60e8a056b2f14545f5f7de474f53ad875a01.png',
            'categories/4c757cacd1ed80f877f702aca1aca8d240d36fa2.png',
            'categories/Mv60hneMznNE1uF0NC3GsXZCv6SyWPa4tmsdFH6W.png',
        ];

        $categories = collect();

        $categoriesData = [
            ['name' => 'كراسي الحلاقة', 'description' => 'كراسي حلاقة احترافية لصالونات الحلاقة والتجميل'],
            ['name' => 'أدوات القص والحلاقة', 'description' => 'ماكينات حلاقة، مقصات، وأدوات القص الاحترافية'],
            ['name' => 'منتجات العناية بالشعر', 'description' => 'شامبو، بلسم، وجميع منتجات العناية بالشعر'],
            ['name' => 'أدوات التصفيف', 'description' => 'مجففات الشعر، مكواة الشعر، وأدوات التصفيف'],
            ['name' => 'قطع غيار وإكسسوارات', 'description' => 'قطع غيار وإكسسوارات للمعدات والأدوات'],
        ];

        foreach ($categoriesData as $index => $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'icon' => $categoryImages[$index] ?? null,
            ]);
            $categories->push($category);
        }

        return $categories;
    }

    private function createProducts($categories)
    {
        $products = collect();

        // الحصول على قائمة صور المنتجات
        $productImages = [
            'products/WhatsApp Image 2025-06-18 at 16.38.19.jpeg',
            'products/WhatsApp Image 2025-06-18 at 16.38.19 (1).jpeg',
            'products/WhatsApp Image 2025-06-18 at 16.38.20.jpeg',
            'products/WhatsApp Image 2025-06-18 at 16.38.20 (1).jpeg',
            'products/WhatsApp Image 2025-06-18 at 16.38.20 (2).jpeg',
            'products/WhatsApp Image 2025-06-18 at 16.38.20 (3).jpeg',
            'products/WhatsApp Image 2025-06-18 at 16.38.20 (4).jpeg',
            'products/WhatsApp Image 2025-06-18 at 16.38.21.jpeg',
            'products/WhatsApp Image 2025-06-16 at 20.24.18.jpeg',
            'products/WhatsApp Image 2025-06-16 at 20.24.18 (1).jpeg',
            'products/WhatsApp Image 2025-06-16 at 20.24.19.jpeg',
            'products/WhatsApp Image 2025-06-16 at 20.24.22.jpeg',
            'products/WhatsApp Image 2025-06-16 at 20.24.23.jpeg',
            'products/WhatsApp Image 2025-06-16 at 20.24.24.jpeg',
            'products/WhatsApp Image 2025-06-16 at 20.24.25.jpeg',
            'products/WhatsApp Image 2025-06-16 at 20.24.26.jpeg',
            'products/WhatsApp Image 2025-06-16 at 20.24.28.jpeg',
            'products/WhatsApp Image 2025-06-16 at 20.24.29.jpeg',
            'products/WhatsApp Image 2025-06-16 at 20.24.30.jpeg',
            'products/WhatsApp Image 2025-06-16 at 20.24.30 (1).jpeg',
        ];

        $productsData = [
            // كراسي الحلاقة
            ['name' => 'كرسي حلاقة كلاسيكي بخطوط منحنية ناعمة', 'price' => 4100, 'category' => 0, 'description' => 'كرسي حلاقة أنيق عتيق الطراز مع خطوط ناعمة ومنحنية، مبطن بجلد طبيعي ومزود بقاعدة دوارة ومضخة هيدروليكية للارتفاع والانخفاض. مثالي للصالونات الراقية والكلاسيكية.'],
            ['name' => 'كرسي صالون أنيق وعصري للراحة القصوى', 'price' => 2700, 'category' => 0, 'description' => 'كرسي صالون عصري مصمم للراحة القصوى مع تنجيد ناعم وقاعدة دوارة. مثالي لصالونات التجميل وقص الشعر مع تصميم أنيق يناسب الديكور العصري.'],
            ['name' => 'كرسي حلاقة بخطوط نظيفة ووسائد ناعمة', 'price' => 4100, 'category' => 0, 'description' => 'كرسي حلاقة بتصميم عصري مع خطوط نظيفة ووسائد ناعمة ومريحة، مصنوع من مواد عالية الجودة مع نظام هيدروليكي للتحكم في الارتفاع والميل.'],
            ['name' => 'كرسي حلاقة كلاسيكي أنيق بخطوط منحنية', 'price' => 1500, 'category' => 0, 'description' => 'كرسي حلاقة كلاسيكي بتصميم أنيق وخطوط منحنية جميلة، مبطن بجلد صناعي عالي الجودة مع قاعدة معدنية قوية ونظام رفع وخفض سهل الاستخدام.'],
            ['name' => 'كرسي صالون عصري وأنيق للراحة القصوى', 'price' => 2400, 'category' => 0, 'description' => 'كرسي صالون عصري مصمم خصيصاً للراحة القصوى مع تنجيد فاخر وتصميم أنيق. يناسب جميع أنواع الصالونات ومراكز التجميل الحديثة.'],
            ['name' => 'كرسي حلاقة أنيق عتيق الطراز بخطوط منحنية', 'price' => 3150, 'category' => 0, 'description' => 'كرسي حلاقة عتيق الطراز مع تصميم أنيق وخطوط منحنية كلاسيكية، مصنوع من أجود الخامات مع تنجيد فاخر ونظام هيدروليكي متطور.'],

            // أدوات القص والحلاقة
            ['name' => 'ماكينة حلاقة احترافية Wahl Professional 5-Star', 'price' => 2500, 'category' => 1, 'description' => 'ماكينة حلاقة احترافية من Wahl مع تقنية 5 نجوم، سلكية/لاسلكية مع قلم سحري للحلاقة الدقيقة. مثالية للاستخدام الاحترافي في الصالونات مع بطارية طويلة المدى وملحقات متنوعة.'],
            ['name' => 'مقص حلاقة احترافي من الستانلس ستيل', 'price' => 800, 'category' => 1, 'description' => 'مقص حلاقة احترافي مصنوع من الستانلس ستيل عالي الجودة، حاد جداً ومريح في الاستخدام. مثالي للقص الدقيق وتصفيف الشعر في الصالونات الاحترافية.'],
            ['name' => 'أمواس حلاقة تقليدية عالية الجودة', 'price' => 350, 'category' => 1, 'description' => 'مجموعة أمواس حلاقة تقليدية حادة وعالية الجودة، مصنوعة من أفضل أنواع المعادن. مثالية للحلاقة التقليدية الدقيقة والاحترافية.'],
            ['name' => 'ماكينة تريم لحية وشارب كهربائية', 'price' => 1200, 'category' => 1, 'description' => 'ماكينة تريم كهربائية متطورة للحية والشارب مع عدة أطوال قابلة للتعديل. سهلة الاستخدام ومثالية للاستخدام المنزلي والاحترافي.'],

            // منتجات العناية بالشعر
            ['name' => 'مجموعة شامبو وبلسم وسيروم Mielle للعناية بالشعر', 'price' => 1800, 'category' => 2, 'description' => 'مجموعة متكاملة للعناية بالشعر من Mielle تشمل شامبو وبلسم وسيروم وماسك كريمي. تغذي الشعر وتحافظ على نعومته ولمعانه الطبيعي. مناسبة لجميع أنواع الشعر.'],
            ['name' => 'شامبو طبيعي عضوي للشعر الجاف والتالف', 'price' => 450, 'category' => 2, 'description' => 'شامبو طبيعي عضوي خالي من الكبريتات، مصمم خصيصاً للشعر الجاف والتالف. يرطب الشعر ويعيد إليه الحيوية واللمعان الطبيعي.'],
            ['name' => 'بلسم مرطب عميق لجميع أنواع الشعر', 'price' => 380, 'category' => 2, 'description' => 'بلسم مرطب عميق يغذي الشعر من الجذور حتى الأطراف، يترك الشعر ناعماً ولامعاً وسهل التسريح. مناسب للاستخدام اليومي.'],
            ['name' => 'زيت طبيعي للشعر بخلاصة الأرجان', 'price' => 650, 'category' => 2, 'description' => 'زيت طبيعي غني بخلاصة الأرجان لتغذية وترطيب الشعر، يحمي من التقصف ويعطي لمعاناً طبيعياً. مثالي للشعر الجاف والمجعد.'],

            // أدوات التصفيف
            ['name' => 'مجفف شعر احترافي 5000W مع موزع هواء', 'price' => 800, 'category' => 3, 'description' => 'مجفف شعر احترافي بقوة 5000 واط مع موزع هواء متطور للحصول على تصفيف احترافي. يحتوي على عدة سرعات ودرجات حرارة مع تقنية الأيونات لحماية الشعر.'],
            ['name' => 'مكواة شعر سيراميك للفرد والتمويج', 'price' => 950, 'category' => 3, 'description' => 'مكواة شعر سيراميك متعددة الاستخدامات للفرد والتمويج مع تحكم رقمي في درجة الحرارة. تحمي الشعر من التلف وتعطي نتائج احترافية.'],
            ['name' => 'فرشاة تصفيف حرارية دوارة', 'price' => 450, 'category' => 3, 'description' => 'فرشاة تصفيف حرارية دوارة لتصفيف سهل وسريع، تعطي حجماً ولمعاناً طبيعياً للشعر. مثالية للاستخدام المنزلي والاحترافي.'],
            ['name' => 'رول شعر حراري مع حامل', 'price' => 320, 'category' => 3, 'description' => 'مجموعة رول شعر حراري بأحجام مختلفة مع حامل عملي. يعطي تمويجات طبيعية وجميلة للشعر مع حفظ التصفيف لفترة طويلة.'],

            // قطع غيار وإكسسوارات
            ['name' => 'رؤوس ماكينة حلاقة قابلة للاستبدال', 'price' => 180, 'category' => 4, 'description' => 'مجموعة رؤوس ماكينة حلاقة قابلة للاستبدال بأحجام مختلفة (1mm، 3mm، 6mm، 9mm). مصنوعة من معدن عالي الجودة وتناسب معظم أنواع ماكينات الحلاقة.'],
            ['name' => 'فرشاة تنظيف شعر طبيعية', 'price' => 120, 'category' => 4, 'description' => 'فرشاة تنظيف شعر مصنوعة من شعيرات طبيعية، مثالية لتوزيع الزيوت الطبيعية في الشعر وإعطاء لمعان صحي. مناسبة لجميع أنواع الشعر.'],
            ['name' => 'مشط معدني للقص الدقيق', 'price' => 85, 'category' => 4, 'description' => 'مشط معدني احترافي للقص الدقيق مع أسنان متقاربة، مثالي للاستخدام مع المقص أو ماكينة الحلاقة للحصول على قصات دقيقة ومتساوية.'],
            ['name' => 'منشفة حلاقة قطنية ناعمة', 'price' => 65, 'category' => 4, 'description' => 'منشفة حلاقة قطنية ناعمة وماصة، مثالية للاستخدام في الصالونات لتنظيف الوجه والرقبة بعد الحلاقة. سهلة الغسل والتعقيم.'],
        ];

        foreach ($productsData as $index => $productData) {
            $product = Product::create([
                'name' => $productData['name'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'category_id' => $categories[$productData['category']]->id,
                'image' => $productImages[$index] ?? $productImages[rand(0, count($productImages) - 1)],
            ]);

            // Add 2-4 additional images for each product
            $additionalImagesCount = rand(2, 4);
            for ($i = 0; $i < $additionalImagesCount; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $productImages[rand(0, count($productImages) - 1)],
                    'sort_order' => $i,
                ]);
            }

            $products->push($product);
        }

        return $products;
    }

    private function createBanners()
    {
        $bannerImages = [
            'banners/d827ce5e551aa9028a435233a46d1952e19d1a52.jpg',
            'banners/59e8ca4b5553315aed7130a2d818afcc6e39bfa7.jpg',
            'banners/25d0e86dc9eae9628e9bf9a4836b53e75e30feb7.jpg',
        ];

        $bannersData = [
            ['title' => 'أدوات الحلاقة الاحترافية', 'description' => 'خصومات تصل إلى 30% على جميع أدوات الحلاقة وأجهزة التصفيف'],
            ['title' => 'كراسي الحلاقة العصرية', 'description' => 'اكتشف مجموعتنا الجديدة من كراسي الحلاقة الفاخرة والعملية'],
            ['title' => 'منتجات العناية بالشعر', 'description' => 'أفضل منتجات العناية بالشعر من علامات تجارية عالمية مع ضمان الجودة'],
        ];

        foreach ($bannersData as $index => $bannerData) {
            Banner::create([
                'image' => $bannerImages[$index],
                'link' => '/products',
            ]);
        }
    }

    private function createOrders($users, $products)
    {
        $customerUsers = $users->slice(1); // تجاهل المدير

        $statuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
        $paymentMethods = ['نقدي عند الاستلام', 'تحويل بنكي', 'فيزا', 'فودافون كاش'];

        // إنشاء طلبات منفصلة لكل مستخدم لتجنب التضارب
        foreach ($customerUsers as $userIndex => $user) {
            // إنشاء 1-3 طلبات لكل مستخدم
            $orderCount = rand(1, 3);

            for ($j = 0; $j < $orderCount; $j++) {
                $orderProducts = $products->random(rand(1, 3))->unique('id');
                $totalPrice = 0;

                $order = Order::create([
                    'user_id' => $user->id,
                    'status' => $statuses[array_rand($statuses)],
                    'total_price' => 0, // سيتم تحديثها لاحقاً
                    'address' => $this->getRandomAddress(),
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'phone1' => $user->phone1,
                    'phone2' => rand(0, 1) ? '01' . rand(100000000, 999999999) : null,
                    'created_at' => now()->subDays(rand(0, 30)),
                ]);

                foreach ($orderProducts as $product) {
                    $quantity = rand(1, 3);
                    $itemPrice = $product->price * $quantity;
                    $totalPrice += $itemPrice;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'user_id' => null, // تعيين null لتجنب القيد unique
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $itemPrice,
                    ]);
                }

                $order->update(['total_price' => $totalPrice]);

                // توقف بعد 25 طلب
                if (Order::count() >= 25) break 2;
            }
        }
    }

    private function createRepairOrders($users)
    {
        $customerUsers = $users->slice(1);
        $statuses = ['pending', 'in_progress', 'completed', 'cancelled'];

        $repairDescriptions = [
            'ماكينة حلاقة لا تعمل ولا تشحن بشكل صحيح',
            'كرسي حلاقة مكسور ولا يرفع أو ينزل',
            'مجفف شعر يسخن زيادة عن اللازم',
            'مكواة شعر لا تسخن والشاشة لا تعمل',
            'كرسي صالون القاعدة لا تدور ويحتاج صيانة',
            'ماكينة تريم لا تعمل والشفرات تحتاج تغيير',
            'مضخة كرسي الحلاقة الهيدروليكية معطلة',
            'فرشاة التصفيف الحرارية لا تسخن',
        ];

        for ($i = 0; $i < 15; $i++) {
            $user = $customerUsers->random();

            RepairOrder::create([
                'user_id' => $user->id,
                'description' => $repairDescriptions[array_rand($repairDescriptions)],
                'phone1' => $user->phone1,
                'phone2' => rand(0, 1) ? '01' . rand(100000000, 999999999) : null,
                'status' => $statuses[array_rand($statuses)],
                'estimated_cost' => rand(100, 1000),
                'final_cost' => rand(0, 1) ? rand(100, 1000) : null,
                'notes' => rand(0, 1) ? 'تم فحص الجهاز وتحديد المشكلة' : null,
                'created_at' => now()->subDays(rand(0, 20)),
            ]);
        }
    }

    private function createNotifications($users)
    {
        $customerUsers = $users->slice(1);

        $notifications = [
            ['title' => 'مرحباً بك في أولاد العلوى للكوافير', 'body' => 'نسعد بانضمامك إلينا! اكتشف أحدث معدات الكوافير والحلاقة', 'type' => 'all_users'],
            ['title' => 'عرض خاص: خصم 25%', 'body' => 'خصم 25% على جميع كراسي الحلاقة وأدوات التصفيف لفترة محدودة', 'type' => 'all_users'],
            ['title' => 'تم شحن طلبك', 'body' => 'تم شحن طلبك رقم #123 وسيصلك خلال 24 ساعة', 'type' => 'specific_users'],
            ['title' => 'تذكير: موعد الصيانة', 'body' => 'لا تنس موعد صيانة معدات الصالون المحدد لك غداً الساعة 3 مساءً', 'type' => 'specific_users'],
            ['title' => 'منتجات جديدة وصلت', 'body' => 'وصلت منتجات جديدة من أحدث أدوات الحلاقة والعناية بالشعر', 'type' => 'all_users'],
        ];

        foreach ($notifications as $notificationData) {
            $sentTo = null;
            if ($notificationData['type'] === 'specific_users') {
                $sentTo = json_encode([$customerUsers->random()->id]);
            }

            Notification::create([
                'title' => $notificationData['title'],
                'body' => $notificationData['body'],
                'type' => $notificationData['type'],
                'sent_to' => $sentTo,
                'status' => 'sent',
                'sent_count' => $notificationData['type'] === 'all_users' ? $customerUsers->count() : 1,
                'sent_at' => now()->subDays(rand(0, 10)),
            ]);
        }
    }

    private function createSettings()
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'أولاد العلوى لمعدات الكوافير والحلاقة'],
            ['key' => 'site_description', 'value' => 'أفضل متجر لمعدات الكوافير والحلاقة وأدوات التجميل الاحترافية في مصر'],
            ['key' => 'contact_phone', 'value' => '01234567890'],
            ['key' => 'contact_email', 'value' => 'info@awladelewa.com'],
            ['key' => 'contact_address', 'value' => 'شارع الجلاء، وسط البلد، القاهرة'],
            ['key' => 'facebook_page', 'value' => 'https://facebook.com/awladelewa'],
            ['key' => 'instagram_page', 'value' => 'https://instagram.com/awladelewa'],
            ['key' => 'delivery_cost', 'value' => '50'],
            ['key' => 'free_delivery_minimum', 'value' => '1000'],
            ['key' => 'maintenance_fee', 'value' => '100'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }

    private function getRandomAddress()
    {
        $addresses = [
            'شارع النيل، المعادي، القاهرة',
            'شارع الهرم، الجيزة',
            'شارع الجامعة، المنصورة، الدقهلية',
            'شارع السلام، الإسكندرية',
            'شارع النصر، طنطا، الغربية',
            'شارع المدينة، أسوان',
            'شارع الكورنيش، الأقصر',
            'شارع التحرير، أسيوط',
            'شارع الجمهورية، سوهاج',
            'شارع النصر، قنا',
        ];

        return $addresses[array_rand($addresses)];
    }

    private function removeUniqueConstraint()
    {
        try {
            Schema::table('order_items', function ($table) {
                $table->dropUnique('cart_items_unique');
            });
        } catch (\Exception $e) {
            // Index might not exist, continue
        }
    }

    private function addUniqueConstraint()
    {
        try {
            Schema::table('order_items', function ($table) {
                $table->unique(['user_id', 'product_id'], 'cart_items_unique');
            });
        } catch (\Exception $e) {
            // Index might already exist, continue
        }
    }
}
