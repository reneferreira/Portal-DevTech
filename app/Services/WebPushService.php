<?php

namespace App\Services;

use App\Models\PushSubscription as PushSubscriptionModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Throwable;

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
        $errors = [];
        $publicKey = $this->normalizeKey(config('services.webpush.public_key'));
        $privateKey = $this->normalizeKey(config('services.webpush.private_key'));

        if (! $publicKey || ! $privateKey) {
            Log::warning('Push notification skipped: VAPID keys are not configured.');

            return compact('sent', 'failed', 'errors');
        }

        try {
            $webPush = new WebPush([
                'VAPID' => [
                    'subject' => config('services.webpush.subject') ?: config('app.url'),
                    'publicKey' => $publicKey,
                    'privateKey' => $privateKey,
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
                $errors[] = $report->getReason();

                Log::warning('Push notification failed.', [
                    'endpoint' => $endpoint,
                    'reason' => $report->getReason(),
                ]);

                if ($report->isSubscriptionExpired() || str_contains($report->getReason(), '404') || str_contains($report->getReason(), '410')) {
                    $subscription?->delete();
                }
            }
        } catch (Throwable $exception) {
            $failed += $subscriptions->count();
            $errors[] = $exception->getMessage();

            Log::error('Push notification dispatch failed.', [
                'message' => $exception->getMessage(),
                'exception' => $exception::class,
            ]);
        }

        return [
            'sent' => $sent,
            'failed' => $failed,
            'errors' => array_values(array_unique(array_filter($errors))),
        ];
    }

    private function normalizeKey(?string $key): ?string
    {
        if (! $key) {
            return null;
        }

        $key = trim($key, " \t\n\r\0\x0B\"'");

        return preg_replace('/\s+/', '', $key) ?: null;
    }
}
