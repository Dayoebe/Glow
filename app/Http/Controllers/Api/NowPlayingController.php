<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class NowPlayingController extends Controller
{
    public function show()
    {
        $station = Setting::get('station', []);
        if (!is_array($station)) {
            $station = [];
        }

        $stream = Setting::get('stream', []);
        if (!is_array($stream)) {
            $stream = [];
        }

        $streamUrl = $stream['stream_url'] ?? ($station['stream_url'] ?? 'https://stream-176.zeno.fm/mwam2yirv1pvv');

        return response()->json([
            'data' => [
                'stream_url' => $streamUrl,
                'is_live' => $stream['is_live'] ?? true,
                'status_message' => $stream['status_message'] ?? 'Broadcasting live now',
                'now_playing_title' => $stream['now_playing_title'] ?? null,
                'now_playing_artist' => $stream['now_playing_artist'] ?? null,
                'show_name' => $stream['show_name'] ?? null,
                'show_time' => $stream['show_time'] ?? null,
                'updated_at' => $stream['updated_at'] ?? null,
            ],
        ]);
    }
}
