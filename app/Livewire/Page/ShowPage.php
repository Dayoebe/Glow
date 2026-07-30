<?php

namespace App\Livewire\Page;

use App\Models\Show\Show;
use App\Models\Show\Category;
use App\Models\Show\ScheduleSlot;
use App\Support\Seo;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPage extends Component
{
    use WithPagination;

    private const SORT_OPTIONS = ['featured', 'popular', 'latest', 'title_asc'];

    public $selectedCategory = 'all';
    public $searchQuery = '';
    public $sortBy = 'featured';

    protected $queryString = [
        'selectedCategory' => ['except' => 'all'],
        'searchQuery' => ['except' => ''],
        'sortBy' => ['except' => 'featured'],
    ];

    public function mount(): void
    {
        $this->normalizeSortBy();
    }

    public function hydrate(): void
    {
        $this->normalizeSortBy();
    }

    public function updatingSearchQuery()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->normalizeSortBy();
        $this->resetPage();
    }

    public function refreshSchedule(): void
    {
        // The on-air widget is derived in render; this targeted poll refreshes it.
    }

    public function getShowsProperty()
    {
        $sortBy = $this->normalizeSortBy();
        $query = Show::with([
                'category',
                'primaryHost',
                'scheduleSlots' => fn ($slots) => $slots->active()
                    ->orderByRaw("case day_of_week
                        when 'monday' then 1 when 'tuesday' then 2 when 'wednesday' then 3
                        when 'thursday' then 4 when 'friday' then 5 when 'saturday' then 6
                        when 'sunday' then 7 else 99 end")
                    ->orderBy('start_time'),
            ])
            ->active();

        if ($this->selectedCategory !== 'all') {
            $query->byCategory($this->selectedCategory);
        }

        if (!empty($this->searchQuery)) {
            $query->where(function ($q) {
                $q->where('shows.title', 'like', "%{$this->searchQuery}%")
                  ->orWhere('shows.description', 'like', "%{$this->searchQuery}%");
            });
        }

        switch ($sortBy) {
            case 'popular':
                $query->orderBy('shows.total_listeners', 'desc');
                break;
            case 'latest':
                $query->latest('shows.created_at');
                break;
            case 'title_asc':
                $query->orderBy('shows.title');
                break;
            default:
                $query->orderBy('shows.is_featured', 'desc')->orderBy('shows.title');
        }

        return $query->paginate(9);
    }

    public function getFeaturedShowProperty()
    {
        return Show::with([
                'category',
                'primaryHost',
                'scheduleSlots' => fn ($slots) => $slots->active()->orderBy('start_time'),
            ])
            ->active()
            ->featured()
            ->latest()
            ->first();
    }

    public function getCategoriesProperty()
    {
        return Category::active()
            ->withCount('shows')
            ->get();
    }

    public function render()
    {
        $shows = $this->shows;
        $currentPage = $shows->currentPage();
        $currentCategory = $this->selectedCategory !== 'all'
            ? $this->categories->firstWhere('slug', $this->selectedCategory)
            : null;
        $hasInvalidCategory = $this->selectedCategory !== 'all' && !$currentCategory;
        $hasNonIndexableFilters = filled($this->searchQuery)
            || $this->sortBy !== 'featured'
            || $hasInvalidCategory;

        $canonicalQuery = [];
        if ($currentCategory) {
            $canonicalQuery['selectedCategory'] = $currentCategory->slug;
        }
        if (!$hasNonIndexableFilters && $currentPage > 1) {
            $canonicalQuery['page'] = $currentPage;
        }

        $canonical = Seo::canonicalUrl(route('shows.index', [], false), $canonicalQuery);
        $pageLabel = $currentPage > 1 && !$hasNonIndexableFilters ? ' - Page ' . $currentPage : '';
        $landingTitle = $currentCategory
            ? $currentCategory->name . ' Programs - Glow 99.1 FM'
            : 'Glow 99.1 FM Programs And Shows';
        $description = $currentCategory
            ? Seo::text(
                $currentCategory->description
                    ?: "Listen to {$currentCategory->name} programs and presenters on Glow 99.1 FM in Akure, Ondo State, Nigeria.",
                165
            )
            : 'Browse Glow 99.1 FM programs and shows from Akure, including public affairs, Yoruba programming, entertainment, sports, interviews, and community radio content.';

        $showItems = $shows->getCollection()
            ->take(40)
            ->values()
            ->map(fn ($show, $index) => [
                '@type' => 'ListItem',
                'position' => ($shows->firstItem() ?? 1) + $index,
                'name' => $show->title,
                'url' => Seo::absoluteUrl(route('shows.show', $show->slug)),
                'description' => Seo::text($show->description, 140),
            ])
            ->all();

        $now = now('Africa/Lagos');
        $today = strtolower($now->format('l'));
        $time = $now->format('H:i:s');
        $todaySlots = ScheduleSlot::with(['show', 'oap'])
            ->active()
            ->whereHas('show', fn ($query) => $query->active())
            ->forDay($today)
            ->overlappingDates($now)
            ->orderBy('start_time')
            ->get()
            ->filter(fn ($slot) => $slot->isActiveOn($now))
            ->values();

        return view('livewire.page.show-page', [
            'shows' => $shows,
            'featuredShow' => $this->featuredShow,
            'currentSlot' => $todaySlots->last(
                fn ($slot) => $slot->start_time <= $time && $slot->end_time > $time
            ),
            'nextSlot' => $todaySlots->first(fn ($slot) => $slot->start_time > $time),
            'categories' => $this->categories->map(function ($cat) {
                return [
                    'slug' => $cat->slug,
                    'name' => $cat->name,
                    'count' => $cat->shows_count,
                    'icon' => $cat->icon,
                    'color' => $cat->color,
                ];
            })->prepend([
                'slug' => 'all',
                'name' => 'All Shows',
                'count' => Show::active()->count(),
                'icon' => 'fas fa-microphone',
                'color' => 'emerald',
            ])->toArray(),
        ])->layout('layouts.app', [
            'title' => $landingTitle . $pageLabel,
            'meta_title' => $landingTitle . $pageLabel,
            'meta_description' => $description,
            'canonical_url' => $canonical,
            'meta_robots' => $hasNonIndexableFilters
                ? config('seo.filtered_robots', 'noindex, follow, noarchive')
                : null,
            'structured_data' => Seo::siteGraph([
                'title' => $landingTitle . $pageLabel,
                'description' => $description,
                'url' => $canonical,
                'type' => 'CollectionPage',
                'mainEntity' => ['@id' => $canonical . '#program-list'],
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Programs', 'url' => route('shows.index')],
                    ...($currentCategory ? [[
                        'name' => $currentCategory->name,
                        'url' => $canonical,
                    ]] : []),
                ],
                'extra' => [
                    [
                        '@type' => 'ItemList',
                        '@id' => $canonical . '#program-list',
                        'name' => $landingTitle,
                        'numberOfItems' => count($showItems),
                        'itemListElement' => $showItems,
                    ],
                ],
            ]),
        ]);
    }

    private function normalizeSortBy(): string
    {
        if (!in_array($this->sortBy, self::SORT_OPTIONS, true)) {
            $this->sortBy = 'featured';
        }

        return $this->sortBy;
    }
}
