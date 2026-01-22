<?php

namespace App\Livewire\Admin\Show;

use App\Models\Show\Category;
use App\Models\Show\OAP;
use App\Models\Show\Show;
use App\Support\CloudinaryUploader;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ShowForm extends Component
{
    use WithFileUploads;

    public $showId = null;
    public $isEditing = false;

    public $title = '';
    public $description = '';
    public $cover_image;
    public $cover_url = '';
    public $category_id = '';
    public $category_choice = '';
    public $primary_host_id = '';
    public $format = 'live';
    public $typical_duration = 60;
    public $content_rating = 'G';
    public $is_featured = false;
    public $tags = '';
    public $new_category_name = '';
    public $new_category_description = '';
    public $creating_category = false;

    public $allCategories = [];
    public $allOaps = [];

    protected function rules()
    {
        return [
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:10',
            'category_id' => 'required|exists:show_categories,id',
            'cover_image' => 'nullable|image|max:5120',
            'cover_url' => 'nullable|string|max:2048',
        ];
    }

    public function mount($showId = null)
    {
        $this->loadCategories();
        $this->allOaps = OAP::active()->get();

        if ($showId) {
            $show = Show::findOrFail($showId);
            $this->showId = $show->id;
            $this->isEditing = true;
            $this->title = $show->title;
            $this->description = $show->description;
            $this->cover_url = $show->cover_image;
            $this->category_id = $show->category_id;
            $this->category_choice = $show->category_id;
            $this->primary_host_id = $show->primary_host_id;
            $this->format = $show->format;
            $this->typical_duration = $show->typical_duration;
            $this->content_rating = $show->content_rating;
            $this->is_featured = $show->is_featured;
            $this->tags = $show->tags ? implode(', ', $show->tags) : '';
        }

        if (!$this->category_choice) {
            $this->category_choice = $this->category_id;
        }
    }

    public function updatedCategoryChoice($value)
    {
        $this->creating_category = $value === '__new__';
        if (!$this->creating_category) {
            $this->category_id = $value;
        }
    }

    public function updatedCoverImage()
    {
        $this->resetErrorBag('cover_image');
        $this->cover_url = '';
        $this->validateOnly('cover_image');
    }

    public function save()
    {
        $this->validate();

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

        if ($this->isEditing) {
            Show::findOrFail($this->showId)->update($data);
            $message = 'Show updated successfully.';
        } else {
            Show::create($data);
            $message = 'Show created successfully.';
        }

        return redirect()
            ->route('admin.shows.index')
            ->with('success', $message);
    }

    private function loadCategories()
    {
        $this->allCategories = Category::active()->get();
    }

    public function createCategory()
    {
        $this->validate([
            'new_category_name' => 'required|min:3|max:255',
            'new_category_description' => 'nullable|max:1000',
        ], [], [
            'new_category_name' => 'category name',
            'new_category_description' => 'category description',
        ]);

        $slug = Str::slug($this->new_category_name);
        $slugExists = Category::where('slug', $slug)->exists();

        if ($slugExists) {
            $this->addError('new_category_name', 'A category with a similar name already exists.');
            return;
        }

        $category = Category::create([
            'name' => $this->new_category_name,
            'slug' => $slug,
            'description' => $this->new_category_description ?: null,
            'icon' => 'fas fa-microphone',
            'color' => 'blue',
            'is_active' => true,
        ]);

        $this->loadCategories();
        $this->category_id = $category->id;
        $this->category_choice = $category->id;
        $this->new_category_name = '';
        $this->new_category_description = '';
        $this->creating_category = false;
    }

    public function render()
    {
        return view('livewire.admin.show.show-form')
            ->layout('layouts.admin', [
                'header' => $this->isEditing ? 'Edit Show' : 'Add Show',
            ]);
    }
}
