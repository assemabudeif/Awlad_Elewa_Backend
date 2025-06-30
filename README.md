<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Awlad Elewa - Laravel Backend & Admin Panel

مشروع Laravel لـ backend و admin panel لتطبيق أولاد العلوى التجاري.

## الميزات الجديدة - نظام الإشعارات

### 🔔 نظام الإشعارات الكامل

تم إضافة نظام إشعارات شامل يدعم Firebase Cloud Messaging (FCM) مع الميزات التالية:

#### ميزات Admin Panel:
- **إدارة الإشعارات**: إنشاء، تعديل، عرض وحذف الإشعارات
- **أنواع الإرسال المختلفة**:
  - جميع المستخدمين
  - مستخدمون محددون
  - متابعي فئات معينة
- **جدولة الإشعارات**: إمكانية جدولة الإشعارات لإرسالها في وقت محدد
- **رفع الصور**: إمكانية إرفاق صور مع الإشعارات
- **إحصائيات الإرسال**: تتبع عدد المستخدمين الذين تم إرسال الإشعار إليهم

#### ميزات API:
- **تسجيل FCM Token**: `/api/fcm-token`
- **إدارة إعدادات الإشعارات**: `/api/notifications/toggle`
- **عرض تاريخ الإشعارات**: `/api/notifications`
- **تحديد الإشعارات كمقروءة**: `/api/notifications/{id}/read`

### 📱 API Endpoints للإشعارات

#### تحديث FCM Token
```http
POST /api/fcm-token
Authorization: Bearer {token}
Content-Type: application/json

{
    "fcm_token": "your_fcm_token_here"
}
```

#### تبديل إعدادات الإشعارات
```http
POST /api/notifications/toggle
Authorization: Bearer {token}
Content-Type: application/json

{
    "enabled": true
}
```

#### الحصول على إعدادات الإشعارات
```http
GET /api/notifications/settings
Authorization: Bearer {token}
```

#### عرض الإشعارات
```http
GET /api/notifications
Authorization: Bearer {token}
```

#### تحديد إشعار كمقروء
```http
POST /api/notifications/{notification_id}/read
Authorization: Bearer {token}
```

#### تحديد جميع الإشعارات كمقروءة
```http
POST /api/notifications/read-all
Authorization: Bearer {token}
```

### ⚙️ الإعداد والتكوين

#### 1. إعداد Firebase
1. قم بإنشاء مشروع في Firebase Console
2. أنشئ service account وحمل ملف JSON
3. ضع الملف في `storage/app/public/`
4. تأكد من تحديث مسار الملف في `config/services.php`

#### 2. متغيرات البيئة
```env
FIREBASE_CREDENTIALS=storage/app/public/your-firebase-credentials.json
FIREBASE_PROJECT_ID=your-project-id
```

#### 3. إعداد Cron Job للإشعارات المجدولة
```bash
# تشغيل في crontab
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

#### 4. تشغيل command يدوياً
```bash
php artisan notifications:send-scheduled
```

### 🗃️ جداول قاعدة البيانات الجديدة

#### جدول `custom_notifications`
- `title`: عنوان الإشعار
- `body`: محتوى الإشعار
- `image`: مسار الصورة (اختياري)
- `type`: نوع الإرسال (all_users, specific_users, category_followers)
- `sent_to`: JSON للمستخدمين أو الفئات المحددة
- `sent_count`: عدد المستخدمين الذين تم الإرسال إليهم
- `status`: حالة الإشعار (draft, scheduled, sent, failed)
- `scheduled_at`: موعد الإرسال المجدول
- `sent_at`: تاريخ الإرسال الفعلي

#### إضافات جدول `users`
- `fcm_token`: رمز FCM للمستخدم
- `notifications_enabled`: تفعيل/إلغاء تفعيل الإشعارات

### 🔧 الخدمات والمكونات

#### `FCMService`
خدمة للتعامل مع Firebase Cloud Messaging:
- إرسال للجهاز الواحد
- إرسال متعدد (حتى 500 جهاز)
- إرسال للـ topics
- الاشتراك/إلغاء الاشتراك في topics

#### `NotificationController` (Admin)
تحكم كامل في إدارة الإشعارات من لوحة الإدارة

#### `NotificationController` (API)
إدارة إعدادات الإشعارات وتاريخها للمستخدمين

### 🎨 واجهة المستخدم

تم إضافة صفحات جديدة في Admin Panel:
- `/admin/notifications` - قائمة الإشعارات
- `/admin/notifications/create` - إنشاء إشعار جديد
- `/admin/notifications/{id}` - عرض تفاصيل الإشعار
- `/admin/notifications/{id}/edit` - تعديل الإشعار

### 📦 الحزم المستخدمة

- `laravel-notification-channels/fcm` - للتعامل مع FCM
- `kreait/firebase-php` - Firebase Admin SDK
- `firebase/php-jwt` - للتوكنات

### 🚀 الاستخدام السريع

1. **إنشاء إشعار جديد**:
   - ادخل على `/admin/notifications/create`
   - املأ البيانات المطلوبة
   - اختر نوع الإرسال
   - احفظ أو أرسل فوراً

2. **للتطبيق المحمول**:
   - سجل FCM token عند تسجيل الدخول
   - فعل الإشعارات من الإعدادات
   - استقبل الإشعارات تلقائياً

---

## الميزات الأساسية (موجودة مسبقاً)

- تسجيل الدخول والتسجيل للمستخدمين
- إدارة المنتجات والفئات
- إدارة الطلبات وطلبات الصيانة
- إدارة البانرات والإعلانات
- إدارة المستخدمين
- إعدادات التطبيق
- API شامل للتطبيق المحمول

## التثبيت

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
```

## إعداد قاعدة البيانات

قم بتحديث ملف `.env` بإعدادات قاعدة البيانات وباقي الخدمات.

## Admin Panel

يمكن الوصول للوحة الإدارة من خلال `/admin` بعد تسجيل الدخول كأدمن.

## API Documentation

API endpoint متاح في `/docs` لمراجعة جميع endpoints المتاحة.
