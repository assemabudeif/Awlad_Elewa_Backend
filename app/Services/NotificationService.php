<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\RepairOrder;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    /**
     * إرسال إشعار عند إنشاء طلب جديد
     */
    public function sendOrderCreatedNotification(Order $order)
    {
        try {
            $user = $order->user;

            // إنشاء إشعار مخصص في قاعدة البيانات
            $notification = Notification::create([
                'title' => 'تم إنشاء طلبك بنجاح',
                'body' => "تم إنشاء طلبك رقم #{$order->id} بقيمة {$order->total_price} ج.م بنجاح. سيتم مراجعته والرد عليك قريباً.",
                'type' => Notification::TYPE_SPECIFIC_USERS,
                'sent_to' => json_encode([$user->id]),
                'status' => Notification::STATUS_SENT,
                'sent_at' => now(),
                'data' => [
                    'order_id' => $order->id,
                    'type' => 'order_created',
                    'total_price' => $order->total_price
                ]
            ]);

            // إرسال FCM للمستخدم
            if ($user->notificationsEnabled()) {
                $result = $this->fcmService->sendToDevice(
                    $user->fcm_token,
                    $notification->title,
                    $notification->body,
                    null,
                    $notification->data
                );

                if ($result['success']) {
                    $notification->update(['sent_count' => 1]);
                }
            }

            // إشعار الأدمن (يمكن إضافة FCM للأدمن لاحقاً)
            Log::info("طلب جديد رقم #{$order->id} من المستخدم {$user->name}");
        } catch (\Exception $e) {
            Log::error('خطأ في إرسال إشعار إنشاء الطلب: ' . $e->getMessage());
        }
    }

    /**
     * إرسال إشعار عند تغيير حالة الطلب
     */
    public function sendOrderStatusChangedNotification(Order $order, $oldStatus, $newStatus)
    {
        try {
            $user = $order->user;

            $statusMessages = [
                'pending' => 'طلبك قيد الانتظار',
                'processing' => 'طلبك قيد المعالجة',
                'shipped' => 'تم شحن طلبك',
                'completed' => 'تم تسليم طلبك بنجاح',
                'cancelled' => 'تم إلغاء طلبك'
            ];

            $title = $statusMessages[$newStatus] ?? 'تم تحديث حالة طلبك';
            $body = "تم تحديث حالة طلبك رقم #{$order->id} إلى: {$title}";

            // إنشاء إشعار مخصص
            $notification = Notification::create([
                'title' => $title,
                'body' => $body,
                'type' => Notification::TYPE_SPECIFIC_USERS,
                'sent_to' => json_encode([$user->id]),
                'status' => Notification::STATUS_SENT,
                'sent_at' => now(),
                'data' => [
                    'order_id' => $order->id,
                    'type' => 'order_status_changed',
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]
            ]);

            // إرسال FCM للمستخدم
            if ($user->notificationsEnabled()) {
                $result = $this->fcmService->sendToDevice(
                    $user->fcm_token,
                    $notification->title,
                    $notification->body,
                    null,
                    $notification->data
                );

                if ($result['success']) {
                    $notification->update(['sent_count' => 1]);
                }
            }
        } catch (\Exception $e) {
            Log::error('خطأ في إرسال إشعار تغيير حالة الطلب: ' . $e->getMessage());
        }
    }

    /**
     * إرسال إشعار عند إنشاء طلب صيانة
     */
    public function sendRepairOrderCreatedNotification(RepairOrder $repairOrder)
    {
        try {
            $user = $repairOrder->user;

            $notification = Notification::create([
                'title' => 'تم إنشاء طلب الصيانة بنجاح',
                'body' => "تم إنشاء طلب صيانة رقم #{$repairOrder->id} بنجاح. سيتم التواصل معك قريباً لتحديد موعد الصيانة.",
                'type' => Notification::TYPE_SPECIFIC_USERS,
                'sent_to' => json_encode([$user->id]),
                'status' => Notification::STATUS_SENT,
                'sent_at' => now(),
                'data' => [
                    'repair_order_id' => $repairOrder->id,
                    'type' => 'repair_order_created',
                    'device_type' => $repairOrder->device_type ?? 'غير محدد'
                ]
            ]);

            // إرسال FCM للمستخدم
            if ($user->notificationsEnabled()) {
                $result = $this->fcmService->sendToDevice(
                    $user->fcm_token,
                    $notification->title,
                    $notification->body,
                    null,
                    $notification->data
                );

                if ($result['success']) {
                    $notification->update(['sent_count' => 1]);
                }
            }

            Log::info("طلب صيانة جديد رقم #{$repairOrder->id} من المستخدم {$user->name}");
        } catch (\Exception $e) {
            Log::error('خطأ في إرسال إشعار إنشاء طلب الصيانة: ' . $e->getMessage());
        }
    }

    /**
     * إرسال إشعار ترحيبي للمستخدم الجديد
     */
    public function sendWelcomeNotification(User $user)
    {
        try {
            $notification = Notification::create([
                'title' => 'مرحباً بك في أولاد العلوى',
                'body' => "أهلاً بك {$user->name}! نسعد بانضمامك إلينا. استكشف منتجاتنا واستمتع بتجربة تسوق مميزة.",
                'type' => Notification::TYPE_SPECIFIC_USERS,
                'sent_to' => json_encode([$user->id]),
                'status' => Notification::STATUS_SENT,
                'sent_at' => now(),
                'data' => [
                    'user_id' => $user->id,
                    'type' => 'welcome'
                ]
            ]);

            // إرسال FCM للمستخدم (إذا كان لديه token)
            if ($user->notificationsEnabled()) {
                $result = $this->fcmService->sendToDevice(
                    $user->fcm_token,
                    $notification->title,
                    $notification->body,
                    null,
                    $notification->data
                );

                if ($result['success']) {
                    $notification->update(['sent_count' => 1]);
                }
            }
        } catch (\Exception $e) {
            Log::error('خطأ في إرسال إشعار الترحيب: ' . $e->getMessage());
        }
    }

    /**
     * إرسال إشعار عام لجميع المستخدمين
     */
    public function sendBroadcastNotification($title, $body, $image = null, $data = [])
    {
        try {
            $notification = Notification::create([
                'title' => $title,
                'body' => $body,
                'image' => $image,
                'type' => Notification::TYPE_ALL_USERS,
                'status' => Notification::STATUS_SENT,
                'sent_at' => now(),
                'data' => $data
            ]);

            // جلب جميع المستخدمين النشطين
            $users = User::where('notifications_enabled', true)
                ->whereNotNull('fcm_token')
                ->get();

            $tokens = $users->pluck('fcm_token')->filter()->toArray();

            if (!empty($tokens)) {
                $result = $this->fcmService->sendToMultiple(
                    $tokens,
                    $title,
                    $body,
                    $image ? asset('storage/' . $image) : null,
                    $data
                );

                $notification->update(['sent_count' => $result['success_count'] ?? 0]);
            }
        } catch (\Exception $e) {
            Log::error('خطأ في إرسال الإشعار العام: ' . $e->getMessage());
        }
    }

    /**
     * إرسال إشعار عند تغيير حالة طلب الصيانة
     */
    public function sendRepairOrderStatusChangedNotification(RepairOrder $repairOrder, $oldStatus, $newStatus)
    {
        try {
            $user = $repairOrder->user;

            $statusMessages = [
                'pending' => 'طلب الصيانة قيد الانتظار',
                'in_progress' => 'طلب الصيانة قيد التنفيذ',
                'completed' => 'تم إكمال صيانة جهازك',
                'cancelled' => 'تم إلغاء طلب الصيانة'
            ];

            $title = $statusMessages[$newStatus] ?? 'تم تحديث حالة طلب الصيانة';
            $body = "تم تحديث حالة طلب الصيانة رقم #{$repairOrder->id} إلى: {$statusMessages[$newStatus]}";

            // إنشاء إشعار مخصص
            $notification = Notification::create([
                'title' => $title,
                'body' => $body,
                'type' => Notification::TYPE_SPECIFIC_USERS,
                'sent_to' => json_encode([$user->id]),
                'status' => Notification::STATUS_SENT,
                'sent_at' => now(),
                'data' => [
                    'repair_order_id' => $repairOrder->id,
                    'type' => 'repair_order_status_changed',
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]
            ]);

            // إرسال FCM للمستخدم
            if ($user->notificationsEnabled()) {
                $result = $this->fcmService->sendToDevice(
                    $user->fcm_token,
                    $notification->title,
                    $notification->body,
                    null,
                    $notification->data
                );

                if ($result['success']) {
                    $notification->update(['sent_count' => 1]);
                }
            }

            Log::info("تم تحديث حالة طلب الصيانة رقم #{$repairOrder->id} من {$oldStatus} إلى {$newStatus}");
        } catch (\Exception $e) {
            Log::error('خطأ في إرسال إشعار تغيير حالة طلب الصيانة: ' . $e->getMessage());
        }
    }
}
