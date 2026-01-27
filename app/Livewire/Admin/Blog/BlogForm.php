<?php

namespace App\Livewire\Admin\Blog;

use App\Models\Blog\Post;
use App\Models\Blog\Category;
use App\Support\CloudinaryUploader;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class BlogForm extends Component
{
    use WithFileUploads;

    public ?Post $post = null;
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
    public $allow_comments = true;
    public $published_at = '';
    public $meta_description = '';
    public $meta_keywords = '';
    public $tags = '';
    public $series = '';
    public $series_order = '';
    public $video_url = '';
    
    public $manualSlug = false;

    protected function rules()
    {
        $rules = [
            'title' => 'required|min:10|max:255',
            'slug' => 'nullable|max:255|unique:blog_posts,slug',
            'excerpt' => 'required|min:20|max:500',
            'content' => 'required|min:100',
            'featured_image' => 'nullable|image|max:2048',
            'featured_image_url' => 'nullable|url',
            'category_id' => 'required|exists:blog_categories,id',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'published_at' => 'nullable|date',
            'meta_description' => 'nullable|max:500',
            'meta_keywords' => 'nullable|max:255',
            'tags' => 'nullable|string',
            'series' => 'nullable|string|max:255',
            'series_order' => 'nullable|integer|min:1',
            'video_url' => 'nullable|url',
        ];

        if ($this->isEditing && $this->post) {
            $rules['slug'] = 'nullable|max:255|unique:blog_posts,slug,' . $this->post->id;
        }

        return $rules;
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->post = Post::findOrFail($id);
            $this->isEditing = true;
            $this->loadPostData();
        } else {
            // Set default published_at to now for new posts
            $this->published_at = now()->format('Y-m-d\TH:i');
        }
    }

    private function loadPostData()
    {
        $this->title = $this->post->title;
        $this->slug = $this->post->slug;
        $this->excerpt = $this->post->excerpt;
        $this->content = $this->post->content;
        $this->existing_image = $this->post->featured_image;
        $this->category_id = $this->post->category_id;
        $this->category_choice = $this->post->category_id;
        $this->is_published = $this->post->is_published;
        $this->is_featured = $this->post->is_featured;
        $this->allow_comments = $this->post->allow_comments;
        $this->published_at = $this->post->published_at ? 
            $this->post->published_at->format('Y-m-d\TH:i') : '';
        $this->meta_description = $this->post->meta_description;
        $this->meta_keywords = $this->post->meta_keywords;
        $this->tags = $this->post->tags ? implode(', ', $this->post->tags) : '';
        $this->series = $this->post->series;
        $this->series_order = $this->post->series_order;
        $this->video_url = $this->post->video_url;
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

    public function clearVideoUrl(): void
    {
        $this->video_url = '';
        $this->resetErrorBag('video_url');
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
            $this->post->update($data);
            $message = 'Blog post updated successfully!';
        } else {
            $data['author_id'] = auth()->id();
            Post::create($data);
            $message = $publishNow ? 
                'Blog post published successfully!' : 
                'Blog post saved as draft!';
        }

        session()->flash('success', $message);
        return redirect()->route('admin.blog.index');
    }

    private function prepareData()
    {
        // Handle image
        $imagePath = $this->existing_image;
        if ($this->featured_image) {
            $imagePath = CloudinaryUploader::uploadImage($this->featured_image, 'blog');
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
            'allow_comments' => $this->allow_comments,
            'published_at' => $this->is_published && $this->published_at ? 
                $this->published_at : null,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'tags' => $tagsArray,
            'series' => $this->series,
            'series_order' => $this->series_order ?: null,
            'video_url' => $this->video_url,
        ];
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
            'icon' => 'fas fa-newspaper',
            'color' => 'purple',
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
        return Category::active()->get();
    }

    public function render()
    {
        return view('livewire.admin.blog.' . ($this->isEditing ? 'edit' : 'create'), [
            'categories' => $this->categories,
        ])->layout('layouts.admin', [
            'header' => $this->isEditing ? 'Edit Blog Post' : 'Create Blog Post'
        ]);
    }
}
