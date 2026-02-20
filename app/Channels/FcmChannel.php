<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;

class FcmChannel
{
    public function send($notifiable, Notification $notification)
    {
        $token = $notifiable->fcm_token;

        if (!$token) {
            return;
        }

        $messagePayload = $notification->toFcm($notifiable);

        $message = CloudMessage::withTarget('token', $token)
            ->withData($messagePayload['data']);

        try {
            app('firebase.messaging')->send($message);
        } catch (\Exception $e) {
            \Log::error('FCM send failed: ' . $e->getMessage());
        }
    }
}