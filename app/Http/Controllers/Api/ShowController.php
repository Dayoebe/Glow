<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Show\Category;
use App\Models\Show\ScheduleSlot;
use App\Models\Show\Show;
use App\Support\RichTextSanitizer;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __construct(private readonly RichTextSanitizer $richTextSanitizer)
    {
    }

    public function index(Request $request)
    {
        $category = $request->query('category', 'all');
        $search = trim((string) $request->query('search', ''));
        $sortBy = $request->query('sort', 'featured');
        $perPage = (int) $request->query('per_page', 9);
        $perPage = min(24, max(6, $perPage));

        $query = Show::with(['category', 'primaryHost', 'scheduleSlots' => function ($query) {
                $query->active()
                    ->orderBy('day_of_week')
                    ->orderBy('start_time');
            }])
            ->active();

        if ($category !== 'all') {
            $query->byCategory($category);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        switch ($sortBy) {
            case 'popular':
                $query->orderBy('total_listeners', 'desc');
                break;
            case 'latest':
                $query->latest();
                break;
            case 'title_asc':
                $query->orderBy('title');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'duration_asc':
                $query->orderBy('typical_duration');
                break;
            case 'duration_desc':
                $query->orderBy('typical_duration', 'desc');
                break;
            case 'format_asc':
                $query->orderBy('format');
                break;
            case 'format_desc':
                $query->orderBy('format', 'desc');
                break;
            case 'category_asc':
            case 'category_desc':
                $query->leftJoin('show_categories', 'shows.category_id', '=', 'show_categories.id')
                    ->select('shows.*');
                if ($sortBy === 'category_asc') {
                    $query->orderBy('show_categories.name');
                } else {
                    $query->orderBy('show_categories.name', 'desc');
                }
                break;
            case 'host_asc':
            case 'host_desc':
                $query->leftJoin('oaps', 'shows.primary_host_id', '=', 'oaps.id')
                    ->select('shows.*')
                    ->orderByRaw('oaps.name is null');
                if ($sortBy === 'host_asc') {
                    $query->orderBy('oaps.name');
                } else {
                    $query->orderBy('oaps.name', 'desc');
                }
                break;
            case 'day_asc':
            case 'day_desc':
                $dayOrder = "case schedule_slots.day_of_week
                    when 'monday' then 1
                    when 'tuesday' then 2
                    when 'wednesday' then 3
                    when 'thursday' then 4
                    when 'friday' then 5
                    when 'saturday' then 6
                    when 'sunday' then 7
                    else 99 end";
                $slotSub = ScheduleSlot::select('show_id')
                    ->selectRaw("min($dayOrder) as min_day_order")
                    ->selectRaw('min(start_time) as min_start_time')
                    ->where('status', 'active')
                    ->groupBy('show_id');
                $query->leftJoinSub($slotSub, 'slot_sort', function ($join) {
                        $join->on('shows.id', '=', 'slot_sort.show_id');
                    })
                    ->select('shows.*')
                    ->orderByRaw('slot_sort.min_day_order is null');
                if ($sortBy === 'day_asc') {
                    $query->orderBy('slot_sort.min_day_order')
                        ->orderBy('slot_sort.min_start_time');
                } else {
                    $query->orderBy('slot_sort.min_day_order', 'desc')
                        ->orderBy('slot_sort.min_start_time', 'desc');
                }
                break;
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('title');
        }

        $shows = $query->paginate($perPage);

        $data = $shows->getCollection()
            ->map(fn ($show) => $this->mapShowCard($show))
            ->values();

        $featuredShow = Show::with(['category', 'primaryHost', 'scheduleSlots' => function ($query) {
                $query->active()
                    ->orderBy('day_of_week')
                    ->orderBy('start_time');
            }])
            ->active()
            ->featured()
            ->latest()
            ->first();

        $categories = Category::active()
            ->withCount('shows')
            ->get()
            ->map(function ($cat) {
                return [
                    'slug' => $cat->slug,
                    'name' => $cat->name,
                    'count' => $cat->shows_count,
                    'icon' => $cat->icon,
                    'color' => $cat->color,
                ];
            })
            ->prepend([
                'slug' => 'all',
                'name' => 'All Shows',
                'count' => Show::active()->count(),
                'icon' => 'fas fa-microphone',
                'color' => 'emerald',
            ])
            ->values()
            ->toArray();

        return response()->json([
            'data' => $data,
            'meta' => [
                'pagination' => [
                    'current_page' => $shows->currentPage(),
                    'last_page' => $shows->lastPage(),
                    'per_page' => $shows->perPage(),
                    'total' => $shows->total(),
                ],
                'featuredShow' => $featuredShow ? $this->mapShowCard($featuredShow) : null,
                'categories' => $categories,
            ],
        ]);
    }

    public function show(string $slug)
    {
        $show = Show::with([
                'category',
                'primaryHost',
                'scheduleSlots' => function ($query) {
                    $query->active()
                        ->orderBy('day_of_week')
                        ->orderBy('start_time');
                },
                'segments',
            ])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'data' => $this->mapShowDetail($show),
        ]);
    }

    public function schedule()
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        $slots = ScheduleSlot::with(['show', 'oap'])
            ->active()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $scheduleByDay = collect($days)->mapWithKeys(function ($day) use ($slots) {
            $daySlots = $slots->get($day, collect())
                ->map(function ($slot) {
                    return [
                        'id' => $slot->id,
                        'day' => $slot->day_of_week,
                        'start_time' => $slot->start_time,
                        'end_time' => $slot->end_time,
                        'time_range' => $slot->time_range,
                        'show' => $slot->show ? [
                            'id' => $slot->show->id,
                            'slug' => $slot->show->slug,
                            'title' => $slot->show->title,
                            'cover_image' => $slot->show->cover_image,
                        ] : null,
                        'host' => $slot->oap ? [
                            'id' => $slot->oap->id,
                            'slug' => $slot->oap->slug,
                            'name' => $slot->oap->name,
                        ] : null,
                    ];
                })
                ->values();

            return [$day => $daySlots];
        });

        return response()->json([
            'data' => $scheduleByDay,
        ]);
    }

    private function mapShowCard(Show $show): array
    {
        $slot = $show->scheduleSlots->first();

        return [
            'id' => $show->id,
            'slug' => $show->slug,
            'title' => $show->title,
            'description' => $show->description,
            'cover_image' => $show->cover_image,
            'category' => $show->category ? [
                'name' => $show->category->name,
                'slug' => $show->category->slug,
                'color' => $show->category->color,
            ] : null,
            'primary_host' => $show->primaryHost ? [
                'name' => $show->primaryHost->name,
                'slug' => $show->primaryHost->slug,
            ] : null,
            'is_featured' => $show->is_featured,
            'format' => $show->format,
            'typical_duration' => $show->typical_duration,
            'total_listeners' => $show->total_listeners,
            'schedule' => $slot ? [
                'day' => $slot->day_of_week,
                'time_range' => $slot->time_range,
            ] : null,
        ];
    }

    private function mapShowDetail(Show $show): array
    {
        return [
            'id' => $show->id,
            'slug' => $show->slug,
            'title' => $show->title,
            'description' => $show->description,
            'full_description' => $this->richTextSanitizer->sanitizeWithLineBreaks($show->full_description),
            'cover_image' => $show->cover_image,
            'promotional_images' => $show->promotional_images,
            'format' => $show->format,
            'content_rating' => $show->content_rating,
            'typical_duration' => $show->typical_duration,
            'tags' => $show->tags,
            'social_media' => $show->social_media,
            'website_url' => $show->website_url,
            'category' => $show->category ? [
                'name' => $show->category->name,
                'slug' => $show->category->slug,
                'color' => $show->category->color,
            ] : null,
            'primary_host' => $show->primaryHost ? [
                'name' => $show->primaryHost->name,
                'slug' => $show->primaryHost->slug,
            ] : null,
            'schedule_slots' => $show->scheduleSlots->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'day' => $slot->day_of_week,
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time,
                    'time_range' => $slot->time_range,
                ];
            })->values(),
            'segments' => $show->segments->map(function ($segment) {
                return [
                    'id' => $segment->id,
                    'title' => $segment->title,
                    'description' => $segment->description,
                    'duration' => $segment->duration,
                    'order' => $segment->order,
                ];
            })->values(),
        ];
    }
}
