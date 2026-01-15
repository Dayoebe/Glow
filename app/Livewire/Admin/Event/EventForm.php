<?php

namespace App\Livewire\Admin\Event;

use App\Models\Event\Event;
use App\Models\Event\EventCategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class EventForm extends Component
{
    use WithFileUploads;

    public ?Event $event = null;
    public bool $isEditing = false;

    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $content = '';
    public $featured_image;
    public $featured_image_url = '';
    public $existing_image = '';
    public $category_id = '';
    public $is_published = false;
    public $is_featured = false;
    public $allow_comments = true;
    public $published_at = '';
    public $start_at = '';
    public $end_at = '';
    public $timezone = '';
    public $venue_name = '';
    public $venue_address = '';
    public $city = '';
    public $state = '';
    public $country = '';
    public $ticket_url = '';
    public $registration_url = '';
    public $capacity = '';
    public $price = '';
    public $meta_description = '';
    public $meta_keywords = '';
    public $tags = '';

    public $manualSlug = false;

    protected function rules()
    {
        $rules = [
            'title' => 'required|min:5|max:255',
            'slug' => 'nullable|max:255|unique:events,slug',
            'excerpt' => 'required|min:20|max:500',
            'content' => 'required|min:50',
            'featured_image' => 'nullable|image|max:2048',
            'featured_image_url' => 'nullable|url',
            'category_id' => 'required|exists:event_categories,id',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'published_at' => 'nullable|date',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'timezone' => 'nullable|string|max:100',
            'venue_name' => 'nullable|string|max:255',
            'venue_address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:120',
            'state' => 'nullable|string|max:120',
            'country' => 'nullable|string|max:120',
            'ticket_url' => 'nullable|url',
            'registration_url' => 'nullable|url',
            'capacity' => 'nullable|integer|min:1',
            'price' => 'nullable|string|max:50',
            'meta_description' => 'nullable|max:500',
            'meta_keywords' => 'nullable|max:255',
            'tags' => 'nullable|string',
        ];

        if ($this->isEditing && $this->event) {
            $rules['slug'] = 'nullable|max:255|unique:events,slug,' . $this->event->id;
        }

        return $rules;
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->event = Event::findOrFail($id);
            $this->isEditing = true;
            $this->loadEventData();
        } else {
            $this->published_at = now()->format('Y-m-d\TH:i');
            $this->start_at = now()->format('Y-m-d\TH:i');
        }
    }

    private function loadEventData()
    {
        $this->title = $this->event->title;
        $this->slug = $this->event->slug;
        $this->excerpt = $this->event->excerpt;
        $this->content = $this->event->content;
        $this->existing_image = $this->event->featured_image;
        $this->category_id = $this->event->category_id;
        $this->is_published = $this->event->is_published;
        $this->is_featured = $this->event->is_featured;
        $this->allow_comments = $this->event->allow_comments;
        $this->published_at = $this->event->published_at ?
            $this->event->published_at->format('Y-m-d\TH:i') : '';
        $this->start_at = $this->event->start_at ?
            $this->event->start_at->format('Y-m-d\TH:i') : '';
        $this->end_at = $this->event->end_at ?
            $this->event->end_at->format('Y-m-d\TH:i') : '';
        $this->timezone = $this->event->timezone;
        $this->venue_name = $this->event->venue_name;
        $this->venue_address = $this->event->venue_address;
        $this->city = $this->event->city;
        $this->state = $this->event->state;
        $this->country = $this->event->country;
        $this->ticket_url = $this->event->ticket_url;
        $this->registration_url = $this->event->registration_url;
        $this->capacity = $this->event->capacity;
        $this->price = $this->event->price;
        $this->meta_description = $this->event->meta_description;
        $this->meta_keywords = $this->event->meta_keywords;
        $this->tags = $this->event->tags ? implode(', ', $this->event->tags) : '';
    }

    public function updatedTitle($value)
    {
        if (!$this->manualSlug) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedSlug()
    {
        $this->manualSlug = true;
    }

    public function save($publishNow = false)
    {
        if ($publishNow) {
            $this->is_published = true;
            if (empty($this->published_at)) {
                $this->published_at = now()->format('Y-m-d\TH:i');
            }
        }

        $this->validate();

        $data = $this->prepareData();

        if ($this->isEditing) {
            $this->event->update($data);
            $message = 'Event updated successfully!';
        } else {
            $data['author_id'] = auth()->id();
            Event::create($data);
            $message = $publishNow ?
                'Event published successfully!' :
                'Event saved as draft!';
        }

        session()->flash('success', $message);
        return redirect()->route('admin.events.index');
    }

    private function prepareData()
    {
        $imagePath = $this->existing_image;
        if ($this->featured_image) {
            $path = $this->featured_image->store('events', 'public');
            $imagePath = asset('storage/' . $path);
        } elseif (!empty($this->featured_image_url)) {
            $imagePath = $this->featured_image_url;
        }

        $tagsArray = null;
        if (!empty($this->tags)) {
            $tagsArray = array_map('trim', explode(',', $this->tags));
        }

        return [
            'title' => $this->title,
            'slug' => $this->slug ?: Str::slug($this->title),
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image' => $imagePath,
            'category_id' => $this->category_id,
            'is_published' => $this->is_published,
            'is_featured' => $this->is_featured,
            'allow_comments' => $this->allow_comments,
            'published_at' => $this->is_published && $this->published_at ?
                $this->published_at : null,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at ?: null,
            'timezone' => $this->timezone ?: null,
            'venue_name' => $this->venue_name ?: null,
            'venue_address' => $this->venue_address ?: null,
            'city' => $this->city ?: null,
            'state' => $this->state ?: null,
            'country' => $this->country ?: null,
            'ticket_url' => $this->ticket_url ?: null,
            'registration_url' => $this->registration_url ?: null,
            'capacity' => $this->capacity ?: null,
            'price' => $this->price ?: null,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'tags' => $tagsArray,
        ];
    }

    public function saveAsDraft()
    {
        $this->is_published = false;
        $this->save(false);
    }

    public function publishNow()
    {
        $this->save(true);
    }

    public function update()
    {
        $this->save(false);
    }

    public function getCategoriesProperty()
    {
        return EventCategory::active()->get();
    }

    public function render()
    {
        return view('livewire.admin.event.' . ($this->isEditing ? 'edit' : 'create'), [
            'categories' => $this->categories,
        ])->layout('layouts.admin', [
            'header' => $this->isEditing ? 'Edit Event' : 'Create Event'
        ]);
    }
}
