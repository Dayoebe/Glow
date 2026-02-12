<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventCategory;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category', 'all');
        $search = trim((string) $request->query('search', ''));
        $type = $request->query('type', 'upcoming'); // upcoming | past | all
        $perPage = (int) $request->query('per_page', 10);
        $perPage = min(24, max(6, $perPage));

        $query = Event::with(['category', 'author'])
            ->published();

        if ($category !== 'all') {
            $query->byCategory($category);
        }

        if ($search !== '') {
            $query->search($search);
        }

        if ($type === 'upcoming') {
            $query->upcoming();
        } elseif ($type === 'past') {
            $query->past();
        }

        $query->orderBy('start_at', $type === 'past' ? 'desc' : 'asc');

        $events = $query->paginate($perPage);

        $data = $events->getCollection()
            ->map(fn ($event) => $this->formatEventCard($event))
            ->values();

        $categories = EventCategory::active()
            ->withCount(['events' => function ($query) {
                $query->published();
            }])
            ->get()
            ->map(function ($cat) {
                return [
                    'slug' => $cat->slug,
                    'name' => $cat->name,
                    'count' => $cat->events_count,
                    'icon' => $cat->icon,
                    'color' => $cat->color,
                ];
            })
            ->prepend([
                'slug' => 'all',
                'name' => 'All Events',
                'count' => Event::published()->count(),
                'icon' => 'fas fa-calendar-alt',
                'color' => 'amber',
            ])
            ->values();

        return response()->json([
            'data' => $data,
            'meta' => [
                'pagination' => [
                    'current_page' => $events->currentPage(),
                    'last_page' => $events->lastPage(),
                    'per_page' => $events->perPage(),
                    'total' => $events->total(),
                ],
                'categories' => $categories,
            ],
        ]);
    }

    public function show(string $slug)
    {
        $event = Event::with(['category', 'author'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Event::published()
            ->where('category_id', $event->category_id)
            ->where('id', '!=', $event->id)
            ->orderBy('start_at', 'asc')
            ->take(3)
            ->get()
            ->map(fn ($item) => $this->formatEventCard($item))
            ->values();

        return response()->json([
            'data' => [
                'id' => $event->id,
                'slug' => $event->slug,
                'title' => $event->title,
                'excerpt' => $event->excerpt,
                'content' => $event->content,
                'featured_image' => $event->featured_image,
                'gallery' => $event->gallery,
                'category' => $event->category ? [
                    'name' => $event->category->name,
                    'slug' => $event->category->slug,
                    'color' => $event->category->color,
                ] : null,
                'author' => $event->author ? [
                    'id' => $event->author->id,
                    'name' => $event->author->name,
                    'avatar' => $event->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($event->author->name),
                    'role' => $event->author->role_label ?? 'Host',
                ] : null,
                'start_at' => $event->start_at?->toDateTimeString(),
                'end_at' => $event->end_at?->toDateTimeString(),
                'date' => $event->formatted_date,
                'time' => $event->formatted_time,
                'timezone' => $event->timezone,
                'venue_name' => $event->venue_name,
                'venue_address' => $event->venue_address,
                'city' => $event->city,
                'state' => $event->state,
                'country' => $event->country,
                'ticket_url' => $event->ticket_url,
                'registration_url' => $event->registration_url,
                'capacity' => $event->capacity,
                'price' => $event->price,
                'views' => $event->views,
                'shares' => $event->shares,
                'tags' => $event->tags,
            ],
            'related' => $related,
        ]);
    }

    private function formatEventCard(Event $event): array
    {
        return [
            'id' => $event->id,
            'slug' => $event->slug,
            'title' => $event->title,
            'excerpt' => $event->excerpt,
            'image' => $event->featured_image,
            'category' => $event->category?->name,
            'category_slug' => $event->category?->slug,
            'category_color' => $event->category?->color,
            'date' => $event->formatted_date,
            'time' => $event->formatted_time,
            'location' => $event->venue_name ?? $event->city ?? 'Venue TBA',
            'ticket_url' => $event->ticket_url,
            'registration_url' => $event->registration_url,
            'is_featured' => $event->is_featured,
        ];
    }
}
