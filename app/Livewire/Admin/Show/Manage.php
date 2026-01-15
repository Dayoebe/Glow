<?php

namespace App\Livewire\Admin\Show;

use App\Models\Show\Show;
use App\Models\Show\Category;
use App\Models\Show\OAP;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Manage extends Component
{
    use WithPagination, WithFileUploads;

    public $view = 'shows'; // shows, oaps, schedule, categories
    public $search = '';
    
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

    protected $queryString = ['view', 'search'];

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

    public function save()
    {
        if ($this->modalType === 'show') {
            $this->saveShow();
        } elseif ($this->modalType === 'oap') {
            $this->saveOap();
        } else {
            $this->saveCategory();
        }
    }

    private function saveShow()
    {
        $this->validate([
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:10',
            'category_id' => 'required|exists:show_categories,id',
        ]);

        $coverPath = $this->cover_url;
        if ($this->cover_image) {
            $coverPath = $this->cover_image->store('shows/covers', 'public');
            $coverPath = asset('storage/' . $coverPath);
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
            $photoPath = $this->oap_photo->store('oaps/photos', 'public');
            $photoPath = asset('storage/' . $photoPath);
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
            OAP::find($this->itemId)->update($data);
            session()->flash('success', 'OAP updated successfully!');
        } else {
            OAP::create($data);
            session()->flash('success', 'OAP created successfully!');
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

    public function delete($type, $id)
    {
        if ($type === 'show') {
            Show::find($id)->delete();
        } elseif ($type === 'oap') {
            OAP::find($id)->delete();
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
            'cat_name', 'cat_description', 'cat_icon', 'cat_color'
        ]);
    }

    public function getShowsProperty()
    {
        return Show::with(['category', 'primaryHost'])
            ->when($this->search, function($q) {
                $q->where('title', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(12);
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
        ];

        if ($this->view === 'shows') {
            $data['shows'] = $this->shows;
        } elseif ($this->view === 'oaps') {
            $data['oaps'] = $this->oaps;
        } elseif ($this->view === 'categories') {
            $data['categories'] = $this->categories;
        }

        return view('livewire.admin.show.manage', $data)
            ->layout('layouts.admin', ['header' => 'Shows & Programs']);
    }
}