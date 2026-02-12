<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FcmService;
use Illuminate\Http\Request;

class AdminPushController extends Controller
{
    public function send(Request $request, FcmService $fcm)
    {
        $data = $request->validate([
            'topic' => 'required|string|in:breaking,shows,now_playing',
            'title' => 'required|string|max:120',
            'body' => 'required|string|max:200',
            'image' => 'nullable|url',
            'payload' => 'array',
        ]);

        $payload = $data['payload'] ?? [];
        $payload['topic'] = $data['topic'];

        $result = $fcm->sendToTopic(
            $data['topic'],
            $data['title'],
            $data['body'],
            $payload,
            $data['image'] ?? null
        );

        if (!$result['ok']) {
            return response()->json([
                'message' => 'Push notification failed.',
                'error' => $result['error'] ?? null,
                'status' => $result['status'] ?? null,
                'response' => $result['body'] ?? null,
            ], 500);
        }

        return response()->json([
            'message' => 'Push notification sent.',
            'response' => $result['body'],
        ]);
    }
}
