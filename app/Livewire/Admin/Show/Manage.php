<?php

namespace App\Livewire\Admin\Show;

use App\Models\Show\Show;
use App\Models\Show\Category;
use App\Models\Show\OAP;
use App\Models\Show\ScheduleSlot;
use App\Models\Show\Segment;
use App\Support\CloudinaryUploader;
use App\Support\PersonProfileSync;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Manage extends Component
{
    use WithPagination, WithFileUploads;

    public $view = 'shows'; // shows, oaps, schedule, categories
    public $search = '';

    public $showSort = 'latest';
    public $showSortDirection = 'desc';
    public $scheduleSort = 'day';
    public $scheduleSortDirection = 'asc';
    
    // Modal state
    public $showModal = false;
    public $modalType = 'show'; // show, oap, category
    public $editMode = false;
    public $itemId = null;

    // Show form
    public $title = '';
    public $description = '';
    public $cover_image;
    public $cover_url = '';
    public $category_id = '';
    public $primary_host_id = '';
    public $format = 'live';
    public $typical_duration = 60;
    public $content_rating = 'G';
    public $is_featured = false;
    public $tags = '';

    // OAP form
    public $oap_name = '';
    public $oap_bio = '';
    public $oap_photo;
    public $oap_photo_url = '';
    public $oap_specializations = '';
    public $oap_email = '';
    public $oap_phone = '';

    // Category form
    public $cat_name = '';
    public $cat_description = '';
    public $cat_icon = 'fas fa-microphone';
    public $cat_color = 'blue';

    // Schedule form
    public $schedule_show_id = '';
    public $schedule_oap_id = '';
    public $schedule_day_of_week = 'monday';
    public $schedule_start_time = '';
    public $schedule_end_time = '';
    public $schedule_start_date = '';
    public $schedule_end_date = '';
    public $schedule_is_recurring = true;
    public $schedule_status = 'active';
    public $schedule_notes = '';

    // Segment form
    public $segment_show_id = '';
    public $segment_title = '';
    public $segment_description = '';
    public $segment_start_time = '';
    public $segment_duration = 5;
    public $segment_type = 'other';

    protected $queryString = [
        'view',
        'search',
        'showSort',
        'showSortDirection',
        'scheduleSort',
        'scheduleSortDirection',
    ];

    public function mount($view = 'shows')
    {
        $this->view = $view;
    }

    public function updatedShowSort()
    {
        $this->resetPage();
    }

    public function updatedShowSortDirection()
    {
        $this->resetPage();
    }

    public function updatedScheduleSort()
    {
        $this->resetPage();
    }

    public function updatedScheduleSortDirection()
    {
        $this->resetPage();
    }

    public function openModal($type, $id = null)
    {
        $this->resetForm();
        $this->modalType = $type;
        
        if ($id) {
            $this->editMode = true;
            $this->itemId = $id;
            $this->{'load' . ucfirst($type)}($id);
        } else {
            $this->editMode = false;
        }
        
        $this->showModal = true;
    }

    private function loadShow($id)
    {
        $show = Show::findOrFail($id);
        $this->title = $show->title;
        $this->description = $show->description;
        $this->cover_url = $show->cover_image;
        $this->category_id = $show->category_id;
        $this->primary_host_id = $show->primary_host_id;
        $this->format = $show->format;
        $this->typical_duration = $show->typical_duration;
        $this->content_rating = $show->content_rating;
        $this->is_featured = $show->is_featured;
        $this->tags = $show->tags ? implode(', ', $show->tags) : '';
    }

    private function loadOap($id)
    {
        $oap = OAP::findOrFail($id);
        $this->oap_name = $oap->name;
        $this->oap_bio = $oap->bio;
        $this->oap_photo_url = $oap->profile_photo;
        $this->oap_specializations = $oap->specializations ? implode(', ', $oap->specializations) : '';
        $this->oap_email = $oap->email;
        $this->oap_phone = $oap->phone;
    }

    private function loadCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->cat_name = $category->name;
        $this->cat_description = $category->description;
        $this->cat_icon = $category->icon;
        $this->cat_color = $category->color;
    }

    private function loadSchedule($id)
    {
        $slot = ScheduleSlot::findOrFail($id);
        $this->schedule_show_id = $slot->show_id;
        $this->schedule_oap_id = $slot->oap_id;
        $this->schedule_day_of_week = $slot->day_of_week;
        $this->schedule_start_time = $slot->start_time;
        $this->schedule_end_time = $slot->end_time;
        $this->schedule_start_date = $slot->start_date?->format('Y-m-d') ?? '';
        $this->schedule_end_date = $slot->end_date?->format('Y-m-d') ?? '';
        $this->schedule_is_recurring = $slot->is_recurring;
        $this->schedule_status = $slot->status;
        $this->schedule_notes = $slot->notes;
    }

    private function loadSegment($id)
    {
        $segment = Segment::findOrFail($id);
        $this->segment_show_id = $segment->show_id;
        $this->segment_title = $segment->title;
        $this->segment_description = $segment->description;
        $this->segment_start_time = $this->minutesToTime($segment->start_minute);
        $this->segment_duration = $segment->duration;
        $this->segment_type = $segment->type;
    }

    public function save()
    {
        if ($this->modalType === 'show') {
            $this->saveShow();
        } elseif ($this->modalType === 'oap') {
            $this->saveOap();
        } elseif ($this->modalType === 'schedule') {
            $this->saveSchedule();
        } elseif ($this->modalType === 'segment') {
            $this->saveSegment();
        } else {
            $this->saveCategory();
        }
    }

    private function saveShow()
    {
        $this->validate([
            'title' => 'required|min:3|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:show_categories,id',
        ]);

        $coverPath = $this->cover_url;
        if ($this->cover_image) {
            $coverPath = CloudinaryUploader::uploadImage($this->cover_image, 'shows/covers');
        }

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'description' => $this->description,
            'cover_image' => $coverPath,
            'category_id' => $this->category_id,
            'primary_host_id' => $this->primary_host_id ?: null,
            'format' => $this->format,
            'typical_duration' => $this->typical_duration,
            'content_rating' => $this->content_rating,
            'is_featured' => $this->is_featured,
            'tags' => !empty($this->tags) ? array_map('trim', explode(',', $this->tags)) : null,
        ];

        if ($this->editMode) {
            Show::find($this->itemId)->update($data);
            session()->flash('success', 'Show updated successfully!');
        } else {
            Show::create($data);
            session()->flash('success', 'Show created successfully!');
        }

        $this->closeModal();
    }

    private function saveOap()
    {
        $this->validate([
            'oap_name' => 'required|min:3|max:255',
            'oap_bio' => 'required|min:10',
        ]);

        $photoPath = $this->oap_photo_url;
        if ($this->oap_photo) {
            $photoPath = CloudinaryUploader::uploadImage($this->oap_photo, 'oaps/photos');
        }

        $data = [
            'name' => $this->oap_name,
            'slug' => Str::slug($this->oap_name),
            'bio' => $this->oap_bio,
            'profile_photo' => $photoPath,
            'specializations' => !empty($this->oap_specializations) 
                ? array_map('trim', explode(',', $this->oap_specializations)) 
                : null,
            'email' => $this->oap_email,
            'phone' => $this->oap_phone,
        ];

        if ($this->editMode) {
            $oap = OAP::find($this->itemId);
            $oap?->update($data);
            session()->flash('success', 'OAP updated successfully!');
        } else {
            $oap = OAP::create($data);
            session()->flash('success', 'OAP created successfully!');
        }

        if (isset($oap) && $oap) {
            PersonProfileSync::fromOap($oap);
        }

        $this->closeModal();
    }

    private function saveCategory()
    {
        $this->validate([
            'cat_name' => 'required|min:3|max:255',
        ]);

        $data = [
            'name' => $this->cat_name,
            'slug' => Str::slug($this->cat_name),
            'description' => $this->cat_description,
            'icon' => $this->cat_icon,
            'color' => $this->cat_color,
        ];

        if ($this->editMode) {
            Category::find($this->itemId)->update($data);
            session()->flash('success', 'Category updated successfully!');
        } else {
            Category::create($data);
            session()->flash('success', 'Category created successfully!');
        }

        $this->closeModal();
    }

    private function saveSchedule()
    {
        $this->validate([
            'schedule_show_id' => 'required|exists:shows,id',
            'schedule_day_of_week' => 'required',
            'schedule_start_time' => 'required|date_format:H:i',
            'schedule_end_time' => 'required|date_format:H:i|after:schedule_start_time',
            'schedule_start_date' => 'nullable|date',
            'schedule_end_date' => 'nullable|date|after_or_equal:schedule_start_date',
            'schedule_is_recurring' => 'boolean',
            'schedule_status' => 'required',
        ]);

        $conflictQuery = ScheduleSlot::where('day_of_week', $this->schedule_day_of_week);
        if ($this->editMode) {
            $conflictQuery->where('id', '!=', $this->itemId);
        }

        $timeConflict = $conflictQuery->get()->first(function ($slot) {
            return $slot->hasConflictWith(
                $this->schedule_start_time,
                $this->schedule_end_time,
                $this->schedule_day_of_week
            );
        });

        if ($timeConflict) {
            $this->addError('schedule_start_time', 'This slot conflicts with another scheduled show.');
            return;
        }

        if ($this->schedule_oap_id) {
            $oapConflict = ScheduleSlot::where('oap_id', $this->schedule_oap_id)
                ->where('day_of_week', $this->schedule_day_of_week)
                ->when($this->editMode, function ($q) {
                    $q->where('id', '!=', $this->itemId);
                })
                ->get()
                ->first(function ($slot) {
                    return $slot->hasConflictWith(
                        $this->schedule_start_time,
                        $this->schedule_end_time,
                        $this->schedule_day_of_week
                    );
                });

            if ($oapConflict) {
                $this->addError('schedule_oap_id', 'Selected OAP is already booked for this time.');
                return;
            }
        }

        $data = [
            'show_id' => $this->schedule_show_id,
            'oap_id' => $this->schedule_oap_id ?: null,
            'day_of_week' => $this->schedule_day_of_week,
            'start_time' => $this->schedule_start_time,
            'end_time' => $this->schedule_end_time,
            'start_date' => $this->schedule_start_date ?: null,
            'end_date' => $this->schedule_end_date ?: null,
            'is_recurring' => $this->schedule_is_recurring,
            'status' => $this->schedule_status,
            'notes' => $this->schedule_notes ?: null,
        ];

        if ($this->editMode) {
            ScheduleSlot::find($this->itemId)->update($data);
            session()->flash('success', 'Schedule updated successfully!');
        } else {
            ScheduleSlot::create($data);
            session()->flash('success', 'Schedule created successfully!');
        }

        $this->closeModal();
    }

    private function saveSegment()
    {
        $this->validate([
            'segment_show_id' => 'required|exists:shows,id',
            'segment_title' => 'required|min:3|max:255',
            'segment_start_time' => 'required|date_format:H:i',
            'segment_duration' => 'required|integer|min:1',
            'segment_type' => 'required',
        ]);

        $order = Segment::where('show_id', $this->segment_show_id)->max('order') ?? 0;
        $startMinute = $this->timeToMinutes($this->segment_start_time);

        $data = [
            'show_id' => $this->segment_show_id,
            'title' => $this->segment_title,
            'description' => $this->segment_description,
            'start_minute' => $startMinute,
            'duration' => $this->segment_duration,
            'type' => $this->segment_type,
            'order' => $this->editMode ? Segment::find($this->itemId)->order : $order + 1,
        ];

        if ($this->editMode) {
            Segment::find($this->itemId)->update($data);
            session()->flash('success', 'Segment updated successfully!');
        } else {
            Segment::create($data);
            session()->flash('success', 'Segment created successfully!');
        }

        $this->closeModal();
    }

    public function delete($type, $id)
    {
        if ($type === 'show') {
            Show::find($id)->delete();
        } elseif ($type === 'oap') {
            OAP::find($id)->delete();
        } elseif ($type === 'schedule') {
            ScheduleSlot::find($id)->delete();
        } elseif ($type === 'segment') {
            Segment::find($id)->delete();
        } else {
            Category::find($id)->delete();
        }
        
        session()->flash('success', ucfirst($type) . ' deleted successfully!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'itemId', 'title', 'description', 'cover_image', 'cover_url',
            'category_id', 'primary_host_id', 'format', 'typical_duration',
            'content_rating', 'is_featured', 'tags',
            'oap_name', 'oap_bio', 'oap_photo', 'oap_photo_url',
            'oap_specializations', 'oap_email', 'oap_phone',
            'cat_name', 'cat_description', 'cat_icon', 'cat_color',
            'schedule_show_id', 'schedule_oap_id', 'schedule_day_of_week',
            'schedule_start_time', 'schedule_end_time', 'schedule_start_date',
            'schedule_end_date', 'schedule_is_recurring', 'schedule_status', 'schedule_notes',
            'segment_show_id', 'segment_title', 'segment_description', 'segment_start_time',
            'segment_duration', 'segment_type'
        ]);
    }

    private function timeToMinutes(string $time): int
    {
        [$hours, $minutes] = array_map('intval', explode(':', $time));
        return ($hours * 60) + $minutes;
    }

    private function minutesToTime(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $mins);
    }

    public function getShowsProperty()
    {
        $query = Show::with(['category', 'primaryHost'])
            ->when($this->search, function($q) {
                $q->where('title', 'like', "%{$this->search}%");
            });

        $direction = $this->showSortDirection === 'asc' ? 'asc' : 'desc';
        $dayOrderSql = "CASE day_of_week
            WHEN 'monday' THEN 1
            WHEN 'tuesday' THEN 2
            WHEN 'wednesday' THEN 3
            WHEN 'thursday' THEN 4
            WHEN 'friday' THEN 5
            WHEN 'saturday' THEN 6
            WHEN 'sunday' THEN 7
            ELSE 8
        END";

        switch ($this->showSort) {
            case 'title':
                $query->orderBy('title', $direction);
                break;
            case 'host':
                $query->leftJoin('oaps', 'oaps.id', '=', 'shows.primary_host_id')
                    ->select('shows.*')
                    ->orderBy('oaps.name', $direction);
                break;
            case 'category':
                $query->leftJoin('show_categories', 'show_categories.id', '=', 'shows.category_id')
                    ->select('shows.*')
                    ->orderBy('show_categories.name', $direction);
                break;
            case 'featured':
                $query->orderBy('is_featured', $direction)
                    ->orderBy('title');
                break;
            case 'duration':
                $query->orderBy('typical_duration', $direction)
                    ->orderBy('title');
                break;
            case 'day':
                $query->addSelect([
                    'next_day_order' => ScheduleSlot::selectRaw("MIN({$dayOrderSql})")
                        ->whereColumn('schedule_slots.show_id', 'shows.id'),
                ])->orderBy('next_day_order', $direction)
                    ->orderBy('title');
                break;
            default:
                $query->latest();
                break;
        }

        return $query->paginate(12);
    }

    public function getOapsProperty()
    {
        return OAP::when($this->search, function($q) {
                $q->where('name', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(12);
    }

    public function getCategoriesProperty()
    {
        return Category::withCount('shows')
            ->when($this->search, function($q) {
                $q->where('name', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(12);
    }

    public function getAllCategoriesProperty()
    {
        return Category::active()->get();
    }

    public function getAllOapsProperty()
    {
        return OAP::active()->get();
    }

    public function getAllShowsProperty()
    {
        return Show::orderBy('title')->get();
    }

    public function getScheduleSlotsProperty()
    {
        $query = ScheduleSlot::with(['show', 'oap'])
            ->when($this->search, function ($q) {
                $q->whereHas('show', function ($show) {
                    $show->where('title', 'like', "%{$this->search}%");
                });
            });

        $direction = $this->scheduleSortDirection === 'desc' ? 'desc' : 'asc';
        $dayOrderSql = "CASE day_of_week
            WHEN 'monday' THEN 1
            WHEN 'tuesday' THEN 2
            WHEN 'wednesday' THEN 3
            WHEN 'thursday' THEN 4
            WHEN 'friday' THEN 5
            WHEN 'saturday' THEN 6
            WHEN 'sunday' THEN 7
            ELSE 8
        END";

        switch ($this->scheduleSort) {
            case 'show':
                $query->leftJoin('shows', 'shows.id', '=', 'schedule_slots.show_id')
                    ->select('schedule_slots.*')
                    ->orderBy('shows.title', $direction);
                break;
            case 'host':
                $query->leftJoin('oaps', 'oaps.id', '=', 'schedule_slots.oap_id')
                    ->select('schedule_slots.*')
                    ->orderBy('oaps.name', $direction);
                break;
            case 'time':
                $query->orderBy('start_time', $direction);
                break;
            case 'status':
                $query->orderBy('status', $direction);
                break;
            case 'day':
            default:
                $query->orderByRaw("{$dayOrderSql} {$direction}")
                    ->orderBy('start_time');
                break;
        }

        return $query->paginate(12);
    }

    public function getSegmentsProperty()
    {
        return Segment::with(['show'])
            ->when($this->search, function ($q) {
                $q->where('title', 'like', "%{$this->search}%");
            })
            ->orderBy('show_id')
            ->orderBy('order')
            ->paginate(12);
    }

    public function getStatsProperty()
    {
        return [
            'total_shows' => Show::count(),
            'active_shows' => Show::where('is_active', true)->count(),
            'total_oaps' => OAP::count(),
            'total_categories' => Category::count(),
        ];
    }

    public function render()
    {
        $data = [
            'stats' => $this->stats,
            'allCategories' => $this->allCategories,
            'allOaps' => $this->allOaps,
            'allShows' => $this->allShows,
        ];

        if ($this->view === 'shows') {
            $data['shows'] = $this->shows;
        } elseif ($this->view === 'oaps') {
            $data['oaps'] = $this->oaps;
        } elseif ($this->view === 'schedule') {
            $data['scheduleSlots'] = $this->scheduleSlots;
        } elseif ($this->view === 'segments') {
            $data['segments'] = $this->segments;
        } elseif ($this->view === 'categories') {
            $data['categories'] = $this->categories;
        }

        return view('livewire.admin.show.manage', $data)
            ->layout('layouts.admin', ['header' => 'Shows & Programs']);
    }
}
