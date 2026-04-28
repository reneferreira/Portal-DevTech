<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use App\Services\WebPushService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PushNotificationController extends Controller
{
    public function index(): View
    {
        return view('admin.push.index', [
            'subscriptionsCount' => PushSubscription::count(),
            'recentSubscriptions' => PushSubscription::latest()->take(10)->get(),
            'publicKeyConfigured' => filled(config('services.webpush.public_key')),
            'privateKeyConfigured' => filled(config('services.webpush.private_key')),
        ]);
    }

    public function store(Request $request, WebPushService $webPush): RedirectResponse
    {
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

        return back()->with(
            'success',
            "Notificacao enviada. Sucesso: {$result['sent']}. Falhas: {$result['failed']}."
        );
    }
}
