<?php

namespace App\Livewire\Admin\Vettas;

use App\Models\Vettas\VettasCategory;
use App\Models\Vettas\VettasPhoto;
use App\Support\CloudinaryUploader;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class PhotoForm extends Component
{
    use WithFileUploads;

    public ?VettasPhoto $photo = null;
    public bool $isEditing = false;

    public string $title = '';
    public string $caption = '';
    public string $description = '';
    public $image;
    public string $existing_image = '';
    public string $category_id = '';
    public string $new_category_name = '';
    public string $new_category_description = '';
    public string $alt_text = '';
    public string $photographer_name = '';
    public string $location = '';
    public string $captured_at = '';
    public int $display_order = 0;
    public bool $is_featured = false;
    public bool $is_published = true;
    public string $published_at = '';

    protected function rules(): array
    {
        $imageRule = $this->isEditing ? 'nullable|image|max:4096' : 'required|image|max:4096';

        return [
            'title' => 'required|string|min:2|max:255',
            'caption' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'image' => $imageRule,
            'category_id' => 'required|exists:vettas_categories,id',
            'alt_text' => 'nullable|string|max:255',
            'photographer_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'captured_at' => 'nullable|date',
            'display_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ];
    }

    public function mount($id = null): void
    {
        if (!$id) {
            $this->published_at = now()->format('Y-m-d\TH:i');
            return;
        }

        $this->photo = VettasPhoto::findOrFail($id);
        $this->isEditing = true;
        $this->title = $this->photo->title;
        $this->caption = (string) ($this->photo->caption ?? '');
        $this->description = (string) ($this->photo->description ?? '');
        $this->existing_image = $this->photo->image_path;
        $this->category_id = (string) $this->photo->category_id;
        $this->alt_text = (string) ($this->photo->alt_text ?? '');
        $this->photographer_name = (string) ($this->photo->photographer_name ?? '');
        $this->location = (string) ($this->photo->location ?? '');
        $this->captured_at = $this->photo->captured_at?->format('Y-m-d') ?? '';
        $this->display_order = (int) $this->photo->display_order;
        $this->is_featured = (bool) $this->photo->is_featured;
        $this->is_published = (bool) $this->photo->is_published;
        $this->published_at = $this->photo->published_at?->format('Y-m-d\TH:i') ?? '';
    }

    public function updatedImage(): void
    {
        $this->resetErrorBag('image');
        $this->validateOnly('image');
    }

    public function createCategory(): void
    {
        $this->validate([
            'new_category_name' => 'required|string|min:2|max:100',
            'new_category_description' => 'nullable|string|max:1000',
        ], [], [
            'new_category_name' => 'category name',
            'new_category_description' => 'category description',
        ]);

        $slug = Str::slug($this->new_category_name);

        if (VettasCategory::where('slug', $slug)->exists()) {
            $this->addError('new_category_name', 'A category with a similar name already exists.');
            return;
        }

        $category = VettasCategory::create([
            'name' => $this->new_category_name,
            'slug' => $slug,
            'description' => $this->new_category_description ?: null,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $this->category_id = (string) $category->id;
        $this->new_category_name = '';
        $this->new_category_description = '';
    }

    public function save()
    {
        $validated = $this->validate();
        $data = $this->prepareData($validated);

        if ($this->isEditing && $this->photo) {
            $this->photo->update($data);
        } else {
            $data['created_by'] = auth()->id();
            VettasPhoto::create($data);
        }

        return redirect()->route('admin.vettas.index')->with('success', $this->isEditing
            ? 'Vettas photo updated successfully.'
            : 'Vettas photo created successfully.');
    }

    private function prepareData(array $validated): array
    {
        $imagePath = $this->existing_image;

        if ($this->image) {
            $imagePath = CloudinaryUploader::uploadImage($this->image, 'vettas/gallery');
        }

        return [
            'category_id' => (int) $validated['category_id'],
            'title' => $validated['title'],
            'caption' => $validated['caption'] ?: null,
            'description' => $validated['description'] ?: null,
            'image_path' => $imagePath,
            'alt_text' => $validated['alt_text'] ?: null,
            'photographer_name' => $validated['photographer_name'] ?: null,
            'location' => $validated['location'] ?: null,
            'captured_at' => $validated['captured_at'] ?: null,
            'display_order' => (int) ($validated['display_order'] ?? 0),
            'is_featured' => (bool) $validated['is_featured'],
            'is_published' => (bool) $validated['is_published'],
            'published_at' => $validated['is_published']
                ? ($validated['published_at'] ?: now())
                : null,
            'updated_by' => auth()->id(),
        ];
    }

    public function getCategoriesProperty()
    {
        $query = VettasCategory::query()->ordered();

        if ($this->isEditing && $this->category_id !== '') {
            $query->where(function ($builder) {
                $builder->where('is_active', true)
                    ->orWhere('id', $this->category_id);
            });
        } else {
            $query->active();
        }

        return $query->get();
    }

    public function render()
    {
        return view('livewire.admin.vettas.' . ($this->isEditing ? 'edit' : 'create'), [
            'categories' => $this->categories,
        ])->layout('layouts.admin', [
            'header' => $this->isEditing ? 'Edit Vettas Photo' : 'Create Vettas Photo',
        ]);
    }
}
