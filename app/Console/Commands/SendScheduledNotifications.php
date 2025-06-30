<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Services\FCMService;
use Carbon\Carbon;

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled notifications that are due';

    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        parent::__construct();
        $this->fcmService = $fcmService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notifications = Notification::where('status', Notification::STATUS_SCHEDULED)
            ->where('scheduled_at', '<=', Carbon::now())
            ->get();

        if ($notifications->isEmpty()) {
            $this->info('No scheduled notifications to send.');
            return 0;
        }

        $sent = 0;
        $failed = 0;

        foreach ($notifications as $notification) {
            $this->info("Sending notification: {$notification->title}");

            $result = $this->sendNotification($notification);

            if ($result['success']) {
                $notification->update([
                    'status' => Notification::STATUS_SENT,
                    'sent_at' => now(),
                    'sent_count' => $result['sent_count']
                ]);
                $sent++;
                $this->info("✓ Notification sent successfully to {$result['sent_count']} users");
            } else {
                $notification->update(['status' => Notification::STATUS_FAILED]);
                $failed++;
                $this->error("✗ Failed to send notification: {$result['error']}");
            }
        }

        $this->info("\nSummary:");
        $this->info("✓ Sent: {$sent}");
        $this->info("✗ Failed: {$failed}");

        return 0;
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
                return ['success' => false, 'error' => 'No valid FCM tokens found', 'sent_count' => 0];
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
                return \App\Models\User::where('notifications_enabled', true)->get();

            case Notification::TYPE_SPECIFIC_USERS:
                $userIds = json_decode($notification->sent_to, true) ?? [];
                return \App\Models\User::whereIn('id', $userIds)
                    ->where('notifications_enabled', true)
                    ->get();

            case Notification::TYPE_CATEGORY_FOLLOWERS:
                // This would need to be implemented based on your follow system
                // For now, return all users
                return \App\Models\User::where('notifications_enabled', true)->get();

            default:
                return collect();
        }
    }
}
