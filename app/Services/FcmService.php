<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FcmService
{
    private const FCM_ENDPOINT = 'https://fcm.googleapis.com/fcm/send';

    public function sendToTopic(string $topic, string $title, string $body, array $data = [], ?string $image = null): array
    {
        $key = config('services.fcm.key');
        if (!$key) {
            return [
                'ok' => false,
                'error' => 'FCM_SERVER_KEY is not configured.',
            ];
        }

        $payload = [
            'to' => '/topics/' . $topic,
            'priority' => 'high',
            'notification' => array_filter([
                'title' => $title,
                'body' => $body,
                'image' => $image,
            ]),
            'data' => array_filter($data, fn ($value) => $value !== null),
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $key,
            'Content-Type' => 'application/json',
        ])->post(self::FCM_ENDPOINT, $payload);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }
}
