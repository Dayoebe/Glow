<?php

namespace App\Livewire\Admin\News;

use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Support\CloudinaryUploader;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class NewsForm extends Component
{
    use WithFileUploads;

    public ?News $news = null;
    public bool $isEditing = false;
    
    // Form fields
    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $content = '';
    public $featured_image;
    public $featured_image_url = '';
    public $existing_image = '';
    public $category_id = '';
    public $category_choice = '';
    public $new_category_name = '';
    public $new_category_description = '';
    public $is_published = false;
    public $is_featured = false;
    public $featured_position = 'hero';
    public $published_at = '';
    public $meta_description = '';
    public $meta_keywords = '';
    public $tags = '';
    public $breaking = 'no';
    public $breaking_until = '';
    
    public $manualSlug = false;

    protected function rules()
    {
        $rules = [
            'title' => 'required|min:10|max:255',
            'slug' => 'nullable|max:255|unique:news,slug',
            'excerpt' => 'required|min:20|max:500',
            'content' => 'required|min:100',
            'featured_image' => 'nullable|image|max:2048',
            'featured_image_url' => 'nullable|url',
            'category_id' => 'required|exists:news_categories,id',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'featured_position' => 'nullable|in:none,hero,secondary,sidebar',
            'published_at' => 'nullable|date',
            'meta_description' => 'nullable|max:500', // Changed from 160 to 500
            'meta_keywords' => 'nullable|max:255',
            'tags' => 'nullable|string',
            'breaking' => 'in:no,breaking,urgent',
            'breaking_until' => 'nullable|date',
        ];

        if ($this->isEditing && $this->news) {
            $rules['slug'] = 'nullable|max:255|unique:news,slug,' . $this->news->id;
        }

        return $rules;
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->news = News::findOrFail($id);
            $this->isEditing = true;
            $this->loadNewsData();
        } else {
            // Set default published_at to now for new articles
            $this->published_at = now()->format('Y-m-d\TH:i');
        }
    }

    private function loadNewsData()
    {
        $this->title = $this->news->title;
        $this->slug = $this->news->slug;
        $this->excerpt = $this->news->excerpt;
        $this->content = $this->news->content;
        $this->existing_image = $this->news->featured_image;
        $this->category_id = $this->news->category_id;
        $this->category_choice = $this->news->category_id;
        $this->is_published = $this->news->is_published;
        $this->is_featured = $this->news->is_featured;
        $this->featured_position = $this->news->featured_position ?: ($this->news->is_featured ? 'hero' : 'none');
        $this->published_at = $this->news->published_at ? 
            $this->news->published_at->format('Y-m-d\TH:i') : '';
        $this->meta_description = $this->news->meta_description;
        $this->meta_keywords = $this->news->meta_keywords;
        $this->tags = $this->news->tags ? implode(', ', $this->news->tags) : '';
        $this->breaking = $this->news->breaking;
        $this->breaking_until = $this->news->breaking_until ? 
            $this->news->breaking_until->format('Y-m-d\TH:i') : '';
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

    public function updatedFeaturedImage()
    {
        $this->resetErrorBag('featured_image');
        $this->featured_image_url = '';
        $this->validateOnly('featured_image');
    }

    public function clearFeaturedImageUpload(): void
    {
        $this->featured_image = null;
        $this->resetErrorBag('featured_image');
    }

    public function clearFeaturedImageUrl(): void
    {
        $this->featured_image_url = '';
        $this->resetErrorBag('featured_image_url');
    }

    public function removeExistingImage(): void
    {
        $this->existing_image = null;
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
            $this->news->update($data);
            $message = 'News article updated successfully!';
        } else {
            $data['author_id'] = auth()->id();
            News::create($data);
            $message = $publishNow ? 
                'News article published successfully!' : 
                'News article saved as draft!';
        }

        session()->flash('success', $message);
        return redirect()->route('admin.news.index');
    }

    private function prepareData()
    {
        // Handle image
        $imagePath = $this->existing_image;
        if ($this->featured_image) {
            $imagePath = CloudinaryUploader::uploadImage($this->featured_image, 'news');
        } elseif (!empty($this->featured_image_url)) {
            $imagePath = $this->featured_image_url;
        }

        // Process tags
        $tagsArray = null;
        if (!empty($this->tags)) {
            $tagsArray = array_map('trim', explode(',', $this->tags));
        }

        $featuredPosition = $this->featured_position ?: 'hero';
        $isFeatured = $this->is_featured;

        if (!$this->canFeature()) {
            $isFeatured = false;
            $featuredPosition = 'none';
        }

        if (!$isFeatured) {
            $featuredPosition = 'none';
        } elseif ($featuredPosition === 'none') {
            $featuredPosition = 'hero';
        }

        return [
            'title' => $this->title,
            'slug' => $this->slug ?: Str::slug($this->title),
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image' => $imagePath,
            'category_id' => $this->category_id,
            'is_published' => $this->is_published,
            'is_featured' => $isFeatured,
            'featured_position' => $featuredPosition,
            'published_at' => $this->is_published && $this->published_at ? 
                $this->published_at : null,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'tags' => $tagsArray,
            'breaking' => $this->breaking,
            'breaking_until' => $this->breaking_until ?: null,
        ];
    }

    public function updatedIsFeatured($value)
    {
        if (!$this->canFeature()) {
            $this->is_featured = false;
            $this->featured_position = 'none';
            return;
        }

        if ($value && $this->featured_position === 'none') {
            $this->featured_position = 'hero';
        }
    }

    public function canFeature(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        $approverIds = \App\Models\Setting::get('content_approvers.ids', []);
        return $user->staffMember && in_array($user->staffMember->id, $approverIds, true);
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
        $slugExists = NewsCategory::where('slug', $slug)->exists();

        if ($slugExists) {
            $this->addError('new_category_name', 'A category with a similar name already exists.');
            return;
        }

        $category = NewsCategory::create([
            'name' => $this->new_category_name,
            'slug' => $slug,
            'description' => $this->new_category_description ?: null,
            'icon' => 'fas fa-newspaper',
            'color' => 'emerald',
            'is_active' => true,
        ]);

        $this->category_id = $category->id;
        $this->category_choice = $category->id;
        $this->new_category_name = '';
        $this->new_category_description = '';
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
        return NewsCategory::active()->get();
    }

    public function render()
    {
        return view('livewire.admin.news.' . ($this->isEditing ? 'edit' : 'create'), [
            'categories' => $this->categories,
        ])->layout('layouts.admin', [
            'header' => $this->isEditing ? 'Edit News Article' : 'Create News Article'
        ]);
    }
}
