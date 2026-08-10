<?php

namespace App\Livewire\Page;

use App\Models\Event\Event;
use App\Models\Event\EventCategory;
use App\Support\Seo;
use Livewire\Component;
use Livewire\WithPagination;

class EventPage extends Component
{
    use WithPagination;

    public $selectedCategory = 'all';

    public $searchQuery = '';

    public $sortBy = 'upcoming';

    protected $queryString = [
        'selectedCategory' => ['except' => 'all'],
        'searchQuery' => ['except' => ''],
        'sortBy' => ['except' => 'upcoming'],
    ];

    public function updatingSearchQuery()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function getEventsProperty()
    {
        $query = Event::with(['category', 'author'])
            ->published();

        if ($this->selectedCategory !== 'all') {
            $query->byCategory($this->selectedCategory);
        }

        if (! empty($this->searchQuery)) {
            $query->search($this->searchQuery);
        }

        if (
            $this->selectedCategory === 'all'
            && empty($this->searchQuery)
            && $this->sortBy === 'upcoming'
            && $this->featuredEvent
        ) {
            $query->whereKeyNot($this->featuredEvent['id']);
        }

        switch ($this->sortBy) {
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'past':
                $query->past()->orderBy('start_at', 'desc');
                break;
            case 'latest':
                $query->orderBy('start_at', 'desc');
                break;
            default:
                $query->upcoming()->orderBy('start_at');
        }

        return $query->paginate(9);
    }

    public function getFeaturedEventProperty()
    {
        $event = Event::with(['category', 'author'])
            ->published()
            ->featured()
            ->orderBy('start_at')
            ->first();

        return $event ? $this->formatEventItem($event) : null;
    }

    public function getUpcomingEventsProperty()
    {
        return Event::published()
            ->upcoming()
            ->orderBy('start_at')
            ->take(5)
            ->get();
    }

    public function getCategoriesProperty()
    {
        return EventCategory::active()
            ->withCount(['events' => function ($query) {
                $query->published();
            }])
            ->get();
    }

    public function getPopularTagsProperty()
    {
        return Event::published()
            ->whereNotNull('tags')
            ->get()
            ->pluck('tags')
            ->flatten()
            ->countBy()
            ->sortDesc()
            ->take(15)
            ->keys()
            ->toArray();
    }

    public function render()
    {
        $events = $this->events;
        $categories = $this->categories;
        $currentPage = $events->currentPage();
        $currentCategory = $this->selectedCategory !== 'all'
            ? $categories->firstWhere('slug', $this->selectedCategory)
            : null;
        $hasInvalidCategory = $this->selectedCategory !== 'all' && ! $currentCategory;
        $hasNonIndexableFilters = filled($this->searchQuery)
            || $this->sortBy !== 'upcoming'
            || $hasInvalidCategory;

        $canonicalQuery = [];
        if ($currentCategory) {
            $canonicalQuery['selectedCategory'] = $currentCategory->slug;
        }
        if (! $hasNonIndexableFilters && $currentPage > 1) {
            $canonicalQuery['page'] = $currentPage;
        }

        $canonical = Seo::canonicalUrl(route('events.index', [], false), $canonicalQuery);
        $pageLabel = $currentPage > 1 && ! $hasNonIndexableFilters ? ' - Page '.$currentPage : '';
        $landingTitle = $currentCategory
            ? $currentCategory->name.' Events - Glow 99.1 FM'
            : 'Events And Community Experiences - Glow 99.1 FM';
        $description = $currentCategory
            ? Seo::text(
                $currentCategory->description
                    ?: "Explore {$currentCategory->name} events and community experiences from Glow 99.1 FM in Akure, Ondo State.",
                165
            )
            : 'Explore Glow 99.1 FM events, live broadcasts, community gatherings, and experiences in Akure and across Ondo State.';

        return view('livewire.page.event-page', [
            'events' => $events,
            'featuredEvent' => $this->featuredEvent,
            'upcomingEvents' => $this->upcomingEvents,
            'categories' => $categories->map(function ($cat) {
                return [
                    'slug' => $cat->slug,
                    'name' => $cat->name,
                    'count' => $cat->events_count,
                    'icon' => $cat->icon,
                    'color' => $cat->color,
                ];
            })->prepend([
                'slug' => 'all',
                'name' => 'All Events',
                'count' => Event::published()->count(),
                'icon' => 'fas fa-calendar-alt',
                'color' => 'amber',
            ])->toArray(),
        ])->layout('layouts.app', [
            'title' => $landingTitle.$pageLabel,
            'meta_title' => $landingTitle.$pageLabel,
            'meta_description' => $description,
            'canonical_url' => $canonical,
            'meta_robots' => $hasNonIndexableFilters
                ? config('seo.filtered_robots', 'noindex, follow, noarchive')
                : null,
        ]);
    }

    private function formatEventItem(Event $event): array
    {
        return [
            'id' => $event->id,
            'title' => $event->title,
            'slug' => $event->slug,
            'excerpt' => $event->excerpt,
            'featured_image' => $event->featured_image,
            'start_at' => $event->start_at,
            'formatted_date' => $event->formatted_date,
            'formatted_time' => $event->formatted_time,
            'venue_name' => $event->venue_name,
            'category' => [
                'name' => $event->category->name,
                'slug' => $event->category->slug,
            ],
            'author' => [
                'name' => $event->author->name,
                'avatar' => $event->author->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($event->author->name),
                'role' => $event->author->role_label ?? 'Organizer',
            ],
            'views' => $event->views,
            'shares' => $event->shares,
        ];
    }
}
