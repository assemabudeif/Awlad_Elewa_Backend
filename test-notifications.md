# 🔔 دليل اختبار الإشعارات التلقائية

## الإشعارات المضافة:

### 1. 📦 **إنشاء طلب جديد**
- **متى:** عند إنشاء طلب جديد عبر API
- **المستقبل:** صاحب الطلب
- **المحتوى:** "تم إنشاء طلبك رقم #ID بقيمة X ج.م بنجاح"

### 2. 📊 **تغيير حالة الطلب** 
- **متى:** عند تغيير حالة الطلب من Admin Panel
- **المستقبل:** صاحب الطلب
- **الحالات:**
  - `pending` → "طلبك قيد الانتظار"
  - `processing` → "طلبك قيد المعالجة"
  - `shipped` → "تم شحن طلبك"
  - `completed` → "تم تسليم طلبك بنجاح"
  - `cancelled` → "تم إلغاء طلبك"

### 3. 🔧 **إنشاء طلب صيانة**
- **متى:** عند إنشاء طلب صيانة جديد عبر API
- **المستقبل:** صاحب الطلب
- **المحتوى:** "تم إنشاء طلب صيانة رقم #ID بنجاح"

### 4. 👋 **إشعار ترحيبي**
- **متى:** عند تحديث FCM token لأول مرة للمستخدمين الجدد (خلال 7 أيام)
- **المستقبل:** المستخدم الجديد
- **المحتوى:** "مرحباً بك في أولاد العلوى!"

## 🧪 طرق الاختبار:

### اختبار إنشاء طلب:
```bash
# 1. تسجيل دخول
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"login": "test@test.com", "password": "password123"}'

# 2. تحديث FCM token
curl -X POST http://127.0.0.1:8000/api/fcm-token \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"fcm_token": "test_fcm_token_123"}'

# 3. إضافة منتج للسلة
curl -X POST http://127.0.0.1:8000/api/cart \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"product_id": 1, "quantity": 2}'

# 4. إنشاء طلب (سيرسل إشعار)
curl -X POST http://127.0.0.1:8000/api/orders \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"address": "العنوان", "payment_method": "cash", "phone1": "01234567890"}'
```

### اختبار تغيير حالة الطلب:
1. ادخل Admin Panel: `/admin/orders`
2. اختر طلب واضغط "تعديل"
3. غير الحالة واحفظ
4. سيتم إرسال إشعار للمستخدم تلقائياً

### اختبار إنشاء طلب صيانة:
```bash
curl -X POST http://127.0.0.1:8000/api/repair-orders \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "device_type=جهاز كمبيوتر" \
  -F "problem_description=لا يعمل" \
  -F "phone1=01234567890" \
  -F "photo=@/path/to/image.jpg"
```

## 📊 تتبع الإشعارات:

### في Admin Panel:
- اذهب إلى `/admin/notifications` لرؤية جميع الإشعارات المرسلة
- يمكنك رؤية عدد المرسل إليهم وحالة الإرسال

### في قاعدة البيانات:
```sql
-- عرض الإشعارات التلقائية
SELECT * FROM custom_notifications 
WHERE status = 'sent' 
ORDER BY created_at DESC;

-- عرض إشعارات مستخدم معين  
SELECT * FROM custom_notifications 
WHERE sent_to LIKE '%"4"%'  -- user_id = 4
ORDER BY created_at DESC;
```

### في Logs:
```bash
tail -f storage/logs/laravel.log | grep "طلب جديد"
```

## 🎯 الملاحظات:

1. **الإشعارات تحفظ في قاعدة البيانات حتى لو فشل FCM**
2. **يتم إرسال FCM فقط للمستخدمين الذين:**
   - لديهم FCM token
   - فعلوا الإشعارات (notifications_enabled = true)
3. **جميع الأخطاء تسجل في Laravel logs**
4. **يمكن إرسال إشعارات إضافية من Admin Panel يدوياً**

## 🔧 إعدادات إضافية:

### لإرسال إشعارات للأدمن:
```php
// في NotificationService يمكن إضافة:
$admins = User::role('admin')->get();
// وإرسال إشعارات لهم
```

### لإضافة أنواع إشعارات جديدة:
1. أضف methods جديدة في `NotificationService`
2. استدعها في المكان المناسب
3. أضف النوع في `data` array للتمييز

---

✅ **جميع الإشعارات التلقائية تعمل الآن!** 