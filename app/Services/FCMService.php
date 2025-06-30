<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;

class FCMService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));
        $this->messaging = $factory->createMessaging();
    }

    /**
     * Send notification to a single device
     */
    public function sendToDevice(string $token, string $title, string $body, ?string $image = null, array $data = [])
    {
        try {
            $notification = Notification::create($title, $body);

            if ($image) {
                $notification = $notification->withImageUrl($image);
            }

            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($notification);

            if (!empty($data)) {
                $message = $message->withData($data);
            }

            $this->messaging->send($message);

            return ['success' => true];
        } catch (MessagingException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        } catch (FirebaseException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send notification to multiple devices
     */
    public function sendToMultiple(array $tokens, string $title, string $body, ?string $image = null, array $data = [])
    {
        try {
            $notification = Notification::create($title, $body);

            if ($image) {
                $notification = $notification->withImageUrl($image);
            }

            $message = CloudMessage::new()->withNotification($notification);

            if (!empty($data)) {
                $message = $message->withData($data);
            }

            // Send to multiple tokens (FCM supports up to 500 tokens per request)
            $chunks = array_chunk($tokens, 500);
            $totalSuccess = 0;
            $errors = [];

            foreach ($chunks as $chunk) {
                try {
                    $result = $this->messaging->sendMulticast($message, $chunk);
                    $totalSuccess += $result->successes()->count();

                    foreach ($result->failures() as $failure) {
                        $errors[] = $failure->error()->getMessage();
                    }
                } catch (MessagingException $e) {
                    $errors[] = $e->getMessage();
                }
            }

            return [
                'success' => $totalSuccess > 0,
                'success_count' => $totalSuccess,
                'total_tokens' => count($tokens),
                'errors' => $errors
            ];
        } catch (FirebaseException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send notification to a topic
     */
    public function sendToTopic(string $topic, string $title, string $body, ?string $image = null, array $data = [])
    {
        try {
            $notification = Notification::create($title, $body);

            if ($image) {
                $notification = $notification->withImageUrl($image);
            }

            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification($notification);

            if (!empty($data)) {
                $message = $message->withData($data);
            }

            $this->messaging->send($message);

            return ['success' => true];
        } catch (MessagingException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        } catch (FirebaseException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Subscribe device to topic
     */
    public function subscribeToTopic(string $token, string $topic)
    {
        try {
            $this->messaging->subscribeToTopic($topic, $token);
            return ['success' => true];
        } catch (MessagingException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Unsubscribe device from topic
     */
    public function unsubscribeFromTopic(string $token, string $topic)
    {
        try {
            $this->messaging->unsubscribeFromTopic($topic, $token);
            return ['success' => true];
        } catch (MessagingException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
