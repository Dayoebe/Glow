<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventAttendance;
use Illuminate\Http\Request;

class EventAttendanceController extends Controller
{
    public function summary(Request $request, string $slug)
    {
        $event = Event::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $goingCount = EventAttendance::where('event_id', $event->id)
            ->where('status', 'going')
            ->sum('party_size');

        $interestedCount = EventAttendance::where('event_id', $event->id)
            ->where('status', 'interested')
            ->count();

        $userStatus = null;
        $partySize = null;
        if ($request->user()) {
            $attendance = EventAttendance::where('event_id', $event->id)
                ->where('user_id', $request->user()->id)
                ->first();
            if ($attendance) {
                $userStatus = $attendance->status;
                $partySize = $attendance->party_size;
            }
        }

        return response()->json([
            'data' => [
                'going' => $goingCount,
                'interested' => $interestedCount,
                'capacity' => $event->capacity,
                'user_status' => $userStatus,
                'party_size' => $partySize,
            ],
        ]);
    }

    public function store(Request $request, string $slug)
    {
        $event = Event::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $data = $request->validate([
            'status' => ['required', 'string', 'in:going,interested,none'],
            'party_size' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        if ($data['status'] === 'none') {
            EventAttendance::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->delete();

            return $this->summary($request, $slug);
        }

        $partySize = $data['party_size'] ?? 1;

        if ($data['status'] === 'going' && $event->capacity) {
            $currentGoing = EventAttendance::where('event_id', $event->id)
                ->where('status', 'going')
                ->sum('party_size');

            $existing = EventAttendance::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->first();

            $previousParty = $existing?->party_size ?? 0;
            $effectiveGoing = $currentGoing - $previousParty + $partySize;

            if ($effectiveGoing > $event->capacity) {
                return response()->json([
                    'message' => 'Event capacity reached. Please reduce party size.',
                ], 422);
            }
        }

        EventAttendance::updateOrCreate(
            ['event_id' => $event->id, 'user_id' => $user->id],
            [
                'status' => $data['status'],
                'party_size' => $partySize,
            ]
        );

        return $this->summary($request, $slug);
    }
}
