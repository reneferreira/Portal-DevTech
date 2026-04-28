<?php

namespace App\Http\Controllers;

use App\Models\PushMessage;
use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PushSubscriptionController extends Controller
{
    public function publicKey(): JsonResponse
    {
        return response()->json([
            'publicKey' => config('services.webpush.public_key'),
        ]);
    }

    public function latestMessage(): JsonResponse
    {
        if (! Schema::hasTable('push_messages')) {
            return response()->json([
                'title' => 'Portal DevTech',
                'body' => 'Tem novidade no portal.',
                'url' => route('home'),
                'icon' => asset('icons/icon-192.png'),
                'badge' => asset('icons/badge-96.png'),
            ]);
        }

        $message = PushMessage::latest()->first();

        return response()->json([
            'title' => $message?->title ?? 'Portal DevTech',
            'body' => $message?->body ?? 'Tem novidade no portal.',
            'url' => $message?->url ?? route('home'),
            'icon' => $message?->icon ?? asset('icons/icon-192.png'),
            'badge' => $message?->badge ?? asset('icons/badge-96.png'),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'endpoint' => ['required', 'url', 'max:500'],
            'keys.p256dh' => ['required', 'string', 'max:255'],
            'keys.auth' => ['required', 'string', 'max:255'],
        ]);

        PushSubscription::updateOrCreate(
            ['endpoint' => $data['endpoint']],
            [
                'user_id' => optional($request->user())->id,
                'public_key' => $data['keys']['p256dh'],
                'auth_token' => $data['keys']['auth'],
                'content_encoding' => 'aes128gcm',
                'user_agent' => (string) $request->userAgent(),
            ]
        );

        return response()->json(['message' => 'Inscricao salva.']);
    }

    public function destroy(Request $request): JsonResponse
    {
        $data = $request->validate([
            'endpoint' => ['required', 'url', 'max:500'],
        ]);

        PushSubscription::where('endpoint', $data['endpoint'])->delete();

        return response()->json(['message' => 'Inscricao removida.']);
    }
}
