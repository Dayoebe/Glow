<?php

namespace App\Livewire\Admin\News;

use App\Models\News\News;
use App\Models\News\NewsCategory;
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
    public $is_published = false;
    public $is_featured = false;
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
        $this->is_published = $this->news->is_published;
        $this->is_featured = $this->news->is_featured;
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
            $path = $this->featured_image->store('news', 'public');
            $imagePath = asset('storage/' . $path);
        } elseif (!empty($this->featured_image_url)) {
            $imagePath = $this->featured_image_url;
        }

        // Process tags
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
            'published_at' => $this->is_published && $this->published_at ? 
                $this->published_at : null,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'tags' => $tagsArray,
            'breaking' => $this->breaking,
            'breaking_until' => $this->breaking_until ?: null,
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