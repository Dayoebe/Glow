<?php

namespace App\Livewire\Admin\Event;

use App\Models\Event\Event;
use App\Models\Event\EventCategory;
use Livewire\Component;
use Livewire\WithPagination;

class EventIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCategory = '';
    public $filterStatus = '';
    public $showDeleteModal = false;
    public $eventToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCategory' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function confirmDelete($eventId)
    {
        $this->eventToDelete = $eventId;
        $this->showDeleteModal = true;
    }

    public function deleteEvent()
    {
        if ($this->eventToDelete) {
            $event = Event::find($this->eventToDelete);

            if ($event) {
                $event->comments()->delete();
                $event->interactions()->delete();
                $event->delete();

                session()->flash('success', 'Event deleted successfully!');
            }
        }

        $this->showDeleteModal = false;
        $this->eventToDelete = null;
    }

    public function togglePublish($eventId)
    {
        $event = Event::find($eventId);

        if ($event) {
            $event->is_published = !$event->is_published;

            if ($event->is_published && !$event->published_at) {
                $event->published_at = now();
            }

            $event->save();

            $status = $event->is_published ? 'published' : 'unpublished';
            session()->flash('success', "Event {$status} successfully!");
        }
    }

    public function toggleFeatured($eventId)
    {
        $event = Event::find($eventId);

        if ($event) {
            if (!$event->is_featured) {
                Event::where('is_featured', true)->update(['is_featured' => false]);
            }

            $event->is_featured = !$event->is_featured;
            $event->save();

            session()->flash('success', 'Featured status updated successfully!');
        }
    }

    public function getEventsProperty()
    {
        $query = Event::with(['category', 'author'])
            ->latest('start_at');

        if (!empty($this->search)) {
            $query->search($this->search);
        }

        if (!empty($this->filterCategory)) {
            $query->where('category_id', $this->filterCategory);
        }

        if ($this->filterStatus === 'published') {
            $query->where('is_published', true);
        } elseif ($this->filterStatus === 'draft') {
            $query->where('is_published', false);
        } elseif ($this->filterStatus === 'featured') {
            $query->where('is_featured', true);
        } elseif ($this->filterStatus === 'upcoming') {
            $query->upcoming();
        } elseif ($this->filterStatus === 'past') {
            $query->past();
        }

        return $query->paginate(10);
    }

    public function getCategoriesProperty()
    {
        return EventCategory::active()->get();
    }

    public function getStatsProperty()
    {
        return [
            'total' => Event::count(),
            'published' => Event::where('is_published', true)->count(),
            'draft' => Event::where('is_published', false)->count(),
            'featured' => Event::where('is_featured', true)->count(),
            'upcoming' => Event::where('start_at', '>=', now())->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.event.index', [
            'events' => $this->events,
            'categories' => $this->categories,
            'stats' => $this->stats,
        ])->layout('layouts.admin', [
            'header' => 'Event Management'
        ]);
    }
}
