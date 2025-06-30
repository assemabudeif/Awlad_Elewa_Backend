<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\Category;
use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function index()
    {
        $notifications = Notification::latest()->paginate(15);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::select('id', 'name', 'email')->get();
        $categories = Category::select('id', 'name')->get();
        return view('admin.notifications.create', compact('users', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:all_users,specific_users,category_followers',
            'sent_to' => 'nullable|array',
            'sent_to.*' => 'integer',
            'scheduled_at' => 'nullable|date|after:now',
            'send_now' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('notifications', 'public');
        }

        // Convert sent_to array to JSON
        if (isset($validated['sent_to'])) {
            $validated['sent_to'] = json_encode($validated['sent_to']);
        }

        // Set status based on send_now or scheduled_at
        if ($request->boolean('send_now')) {
            $validated['status'] = Notification::STATUS_SENT;
            $validated['sent_at'] = now();
        } elseif ($validated['scheduled_at']) {
            $validated['status'] = Notification::STATUS_SCHEDULED;
        } else {
            $validated['status'] = Notification::STATUS_DRAFT;
        }

        $notification = Notification::create($validated);

        // Send immediately if requested
        if ($request->boolean('send_now')) {
            $this->sendNotification($notification);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'تم إنشاء الإشعار بنجاح');
    }

    public function show(Notification $notification)
    {
        return view('admin.notifications.show', compact('notification'));
    }

    public function edit(Notification $notification)
    {
        if ($notification->status === Notification::STATUS_SENT) {
            return redirect()->back()->with('error', 'لا يمكن تعديل إشعار تم إرساله بالفعل');
        }

        $users = User::select('id', 'name', 'email')->get();
        $categories = Category::select('id', 'name')->get();
        $notification->sent_to = json_decode($notification->sent_to, true) ?? [];

        return view('admin.notifications.edit', compact('notification', 'users', 'categories'));
    }

    public function update(Request $request, Notification $notification)
    {
        if ($notification->status === Notification::STATUS_SENT) {
            return redirect()->back()->with('error', 'لا يمكن تعديل إشعار تم إرساله بالفعل');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:all_users,specific_users,category_followers',
            'sent_to' => 'nullable|array',
            'sent_to.*' => 'integer',
            'scheduled_at' => 'nullable|date|after:now',
            'send_now' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($notification->image) {
                Storage::disk('public')->delete($notification->image);
            }
            $validated['image'] = $request->file('image')->store('notifications', 'public');
        }

        // Convert sent_to array to JSON
        if (isset($validated['sent_to'])) {
            $validated['sent_to'] = json_encode($validated['sent_to']);
        }

        // Set status based on send_now or scheduled_at
        if ($request->boolean('send_now')) {
            $validated['status'] = Notification::STATUS_SENT;
            $validated['sent_at'] = now();
        } elseif ($validated['scheduled_at']) {
            $validated['status'] = Notification::STATUS_SCHEDULED;
        } else {
            $validated['status'] = Notification::STATUS_DRAFT;
        }

        $notification->update($validated);

        // Send immediately if requested
        if ($request->boolean('send_now')) {
            $this->sendNotification($notification);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'تم تحديث الإشعار بنجاح');
    }

    public function destroy(Notification $notification)
    {
        if ($notification->status === Notification::STATUS_SENT) {
            return redirect()->back()->with('error', 'لا يمكن حذف إشعار تم إرساله بالفعل');
        }

        // Delete image if exists
        if ($notification->image) {
            Storage::disk('public')->delete($notification->image);
        }

        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'تم حذف الإشعار بنجاح');
    }

    public function send(Notification $notification)
    {
        if ($notification->status === Notification::STATUS_SENT) {
            return redirect()->back()->with('error', 'تم إرسال هذا الإشعار بالفعل');
        }

        $result = $this->sendNotification($notification);

        if ($result['success']) {
            $notification->update([
                'status' => Notification::STATUS_SENT,
                'sent_at' => now(),
                'sent_count' => $result['sent_count']
            ]);

            return redirect()->back()->with('success', "تم إرسال الإشعار بنجاح لـ {$result['sent_count']} مستخدم");
        } else {
            $notification->update(['status' => Notification::STATUS_FAILED]);
            return redirect()->back()->with('error', 'فشل في إرسال الإشعار: ' . $result['error']);
        }
    }

    private function sendNotification(Notification $notification)
    {
        try {
            $users = $this->getTargetUsers($notification);
            $tokens = $users->whereNotNull('fcm_token')
                ->where('notifications_enabled', true)
                ->pluck('fcm_token')
                ->filter()
                ->values()
                ->toArray();

            if (empty($tokens)) {
                return ['success' => false, 'error' => 'لا توجد رموز FCM صالحة للإرسال', 'sent_count' => 0];
            }

            $result = $this->fcmService->sendToMultiple(
                $tokens,
                $notification->title,
                $notification->body,
                $notification->image ? asset('storage/' . $notification->image) : null,
                $notification->data ?? []
            );

            return [
                'success' => $result['success'],
                'sent_count' => $result['success_count'] ?? 0,
                'error' => $result['error'] ?? null
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage(), 'sent_count' => 0];
        }
    }

    private function getTargetUsers(Notification $notification)
    {
        switch ($notification->type) {
            case Notification::TYPE_ALL_USERS:
                return User::where('notifications_enabled', true)->get();

            case Notification::TYPE_SPECIFIC_USERS:
                $userIds = json_decode($notification->sent_to, true) ?? [];
                return User::whereIn('id', $userIds)
                    ->where('notifications_enabled', true)
                    ->get();

            case Notification::TYPE_CATEGORY_FOLLOWERS:
                // This would need to be implemented based on your follow system
                // For now, return all users
                return User::where('notifications_enabled', true)->get();

            default:
                return collect();
        }
    }
}
