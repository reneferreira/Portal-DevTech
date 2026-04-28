<?php

namespace App\Services;

use App\Models\PushSubscription as PushSubscriptionModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class WebPushService
{
    public function sendToAll(array $payload): array
    {
        return $this->send(PushSubscriptionModel::query()->latest()->get(), $payload);
    }

    public function send(Collection $subscriptions, array $payload): array
    {
        $sent = 0;
        $failed = 0;

        if (! config('services.webpush.public_key') || ! config('services.webpush.private_key')) {
            Log::warning('Push notification skipped: VAPID keys are not configured.');

            return compact('sent', 'failed');
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => config('services.webpush.subject'),
                'publicKey' => config('services.webpush.public_key'),
                'privateKey' => config('services.webpush.private_key'),
            ],
        ]);

        foreach ($subscriptions as $subscription) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $subscription->endpoint,
                    'publicKey' => $subscription->public_key,
                    'authToken' => $subscription->auth_token,
                    'contentEncoding' => $subscription->content_encoding ?: 'aes128gcm',
                ]),
                json_encode($payload, JSON_UNESCAPED_UNICODE)
            );
        }

        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();
            $subscription = $subscriptions->firstWhere('endpoint', $endpoint);

            if ($report->isSuccess()) {
                $sent++;
                $subscription?->forceFill(['last_used_at' => now()])->save();
                continue;
            }

            $failed++;
            Log::warning('Push notification failed.', [
                'endpoint' => $endpoint,
                'reason' => $report->getReason(),
            ]);

            if ($report->isSubscriptionExpired()) {
                $subscription?->delete();
            }
        }

        return compact('sent', 'failed');
    }
}
