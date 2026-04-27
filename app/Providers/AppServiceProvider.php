<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $publicUrl = function (): string {
            $configuredUrl = rtrim((string) config('app.url'), '/');

            if ($configuredUrl && ! str_contains($configuredUrl, 'localhost')) {
                return $configuredUrl;
            }

            if (! app()->runningInConsole() && (app()->environment('production') || env('VERCEL'))) {
                return 'https://' . request()->getHttpHost();
            }

            return $configuredUrl ?: 'http://localhost';
        };

        if (app()->environment('production') || env('VERCEL')) {
            URL::forceRootUrl($publicUrl());
            URL::forceScheme('https');
        }

        VerifyEmail::createUrlUsing(function ($notifiable) use ($publicUrl) {
            $relativeUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ],
                false
            );

            return $publicUrl() . $relativeUrl;
        });
    }
}
