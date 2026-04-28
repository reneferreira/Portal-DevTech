<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use App\Services\WebPushService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PushNotificationController extends Controller
{
    public function index(): View
    {
        $subscriptionsTableExists = Schema::hasTable('push_subscriptions');

        return view('admin.push.index', [
            'subscriptionsTableExists' => $subscriptionsTableExists,
            'subscriptionsCount' => $subscriptionsTableExists ? PushSubscription::count() : 0,
            'recentSubscriptions' => $subscriptionsTableExists ? PushSubscription::latest()->take(10)->get() : collect(),
            'publicKeyConfigured' => filled(config('services.webpush.public_key')),
            'privateKeyConfigured' => filled(config('services.webpush.private_key')),
        ]);
    }

    public function store(Request $request, WebPushService $webPush): RedirectResponse
    {
        if (! Schema::hasTable('push_subscriptions')) {
            return back()->with('error', 'Execute as migrations antes de enviar notificacoes push.');
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'max:240'],
            'url' => ['nullable', 'url'],
        ]);

        $result = $webPush->sendToAll([
            'title' => $data['title'],
            'body' => $data['body'],
            'url' => $data['url'] ?: route('home'),
            'icon' => asset('icons/icon-192.png'),
            'badge' => asset('icons/badge-96.png'),
        ]);

        $message = "Notificacao enviada. Sucesso: {$result['sent']}. Falhas: {$result['failed']}.";

        if (! empty($result['errors'])) {
            $message .= ' Motivo: ' . implode(' | ', array_slice($result['errors'], 0, 2));
        }

        return back()->with($result['failed'] > 0 ? 'error' : 'success', $message);
    }
}
