<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * Update FCM token for authenticated user
     */
    public function updateFcmToken(Request $request)
    {

        $request->validate([
            'fcm_token' => 'required|string'
        ]);

        $user = User::find(Auth::user()->id);
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'message' => 'FCM token updated successfully',
            'status' => 'success'
        ], 200);
    }

    /**
     * Toggle notifications for authenticated user
     */
    public function toggleNotifications(Request $request)
    {
        $request->validate([
            'enabled' => 'required|boolean'
        ]);

        $user = User::find(Auth::user()->id);
        $user->notifications_enabled = $request->enabled;
        $user->save();

        return response()->json([
            'message' => 'Notification settings updated successfully',
            'status' => 'success',
            'notifications_enabled' => $user->notifications_enabled
        ], 200);
    }

    /**
     * Get user's notification settings
     */
    public function getSettings()
    {
        $user = User::find(Auth::user()->id);

        return response()->json([
            'notifications_enabled' => $user->notifications_enabled,
            'has_fcm_token' => !empty($user->fcm_token),
            'status' => 'success'
        ], 200);
    }

    /**
     * Get user's notifications history (Laravel notifications)
     */
    public function getNotifications()
    {
        $user = User::find(Auth::user()->id);
        $notifications = $user->notifications()->paginate(20);

        return response()->json([
            'notifications' => $notifications,
            'status' => 'success'
        ], 200);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|string'
        ]);

        $user = User::find(Auth::user()->id);
        $notification = $user->notifications()->find($request->notification_id);

        if (!$notification) {
            return response()->json([
                'message' => 'Notification not found',
                'status' => 'error'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read',
            'status' => 'success'
        ], 200);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = User::find(Auth::user()->id);
        $user->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'All notifications marked as read',
            'status' => 'success'
        ], 200);
    }
}
