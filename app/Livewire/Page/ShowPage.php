<?php

namespace App\Livewire\Page;

use App\Models\Show\Show;
use App\Models\Show\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPage extends Component
{
    use WithPagination;

    public $selectedCategory = 'all';
    public $searchQuery = '';
    public $sortBy = 'featured';

    protected $queryString = [
        'selectedCategory' => ['except' => 'all'],
        'searchQuery' => ['except' => ''],
        'sortBy' => ['except' => 'featured'],
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

    public function getShowsProperty()
    {
        $query = Show::with(['category', 'primaryHost'])
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

        switch ($this->sortBy) {
            case 'popular':
                $query->orderBy('shows.total_listeners', 'desc');
                break;
            case 'latest':
                $query->latest('shows.created_at');
                break;
            case 'title_asc':
                $query->orderBy('shows.title');
                break;
            case 'title_desc':
                $query->orderBy('shows.title', 'desc');
                break;
            case 'duration_asc':
                $query->orderBy('shows.typical_duration');
                break;
            case 'duration_desc':
                $query->orderBy('shows.typical_duration', 'desc');
                break;
            case 'format_asc':
                $query->orderBy('shows.format');
                break;
            case 'format_desc':
                $query->orderBy('shows.format', 'desc');
                break;
            case 'category_asc':
            case 'category_desc':
                $query->leftJoin('show_categories', 'shows.category_id', '=', 'show_categories.id')
                    ->select('shows.*');
                if ($this->sortBy === 'category_asc') {
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
                if ($this->sortBy === 'host_asc') {
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
                $slotSub = \App\Models\Show\ScheduleSlot::select('show_id')
                    ->selectRaw("min($dayOrder) as min_day_order")
                    ->selectRaw('min(start_time) as min_start_time')
                    ->where('status', 'active')
                    ->groupBy('show_id');
                $query->leftJoinSub($slotSub, 'slot_sort', function ($join) {
                        $join->on('shows.id', '=', 'slot_sort.show_id');
                    })
                    ->select('shows.*')
                    ->orderByRaw('slot_sort.min_day_order is null');
                if ($this->sortBy === 'day_asc') {
                    $query->orderBy('slot_sort.min_day_order')
                        ->orderBy('slot_sort.min_start_time');
                } else {
                    $query->orderBy('slot_sort.min_day_order', 'desc')
                        ->orderBy('slot_sort.min_start_time', 'desc');
                }
                break;
            default:
                $query->orderBy('shows.is_featured', 'desc')->orderBy('shows.title');
        }

        return $query->paginate(9);
    }

    public function getFeaturedShowProperty()
    {
        return Show::with(['category', 'primaryHost'])
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
        return view('livewire.page.show-page', [
            'shows' => $this->shows,
            'featuredShow' => $this->featuredShow,
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
        ])->layout('layouts.app', ['title' => 'Shows & Programs - Glow FM']);
    }
}
