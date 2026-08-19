<?php

namespace App\Livewire\Admin\Vettas;

use App\Models\Vettas\VettasCategory;
use Illuminate\Support\Str;
use Livewire\Component;

class CategoryForm extends Component
{
    public ?int $categoryId = null;
    public bool $isEditing = false;

    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $eyebrow = '';
    public string $headline = '';
    public string $seo_title = '';
    public string $meta_description = '';
    public array $highlights = [];
    public array $faqs = [];
    public int $sort_order = 0;
    public bool $is_active = true;

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|min:2|max:100',
            'slug' => 'required|string|max:100|unique:vettas_categories,slug',
            'description' => 'nullable|string|max:1000',
            'eyebrow' => 'nullable|string|max:100',
            'headline' => 'nullable|string|max:180',
            'seo_title' => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:320',
            'highlights' => 'array|max:8',
            'highlights.*' => 'nullable|string|max:180',
            'faqs' => 'array|max:8',
            'faqs.*.question' => 'nullable|string|max:180',
            'faqs.*.answer' => 'nullable|string|max:800',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];

        if ($this->isEditing && $this->categoryId) {
            $rules['slug'] = 'required|string|max:100|unique:vettas_categories,slug,' . $this->categoryId;
        }

        return $rules;
    }

    public function mount($categoryId = null): void
    {
        if (!$categoryId) {
            return;
        }

        $category = VettasCategory::findOrFail($categoryId);

        $this->categoryId = $category->id;
        $this->isEditing = true;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = (string) ($category->description ?? '');
        $this->eyebrow = (string) ($category->eyebrow ?? '');
        $this->headline = (string) ($category->headline ?? '');
        $this->seo_title = (string) ($category->seo_title ?? '');
        $this->meta_description = (string) ($category->meta_description ?? '');
        $this->highlights = $category->highlights ?? [];
        $this->faqs = $category->faqs ?? [];
        $this->sort_order = (int) $category->sort_order;
        $this->is_active = (bool) $category->is_active;
    }

    public function updatedName(string $value): void
    {
        if (!$this->isEditing || $this->slug === '') {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $validated = $this->validate();
        $validated['highlights'] = collect($validated['highlights'] ?? [])->map(fn ($item) => trim((string) $item))->filter()->values()->all();
        $validated['faqs'] = collect($validated['faqs'] ?? [])->map(fn ($faq) => [
            'question' => trim((string) ($faq['question'] ?? '')),
            'answer' => trim((string) ($faq['answer'] ?? '')),
        ])->filter(fn ($faq) => $faq['question'] !== '' && $faq['answer'] !== '')->values()->all();

        if ($this->isEditing) {
            VettasCategory::findOrFail($this->categoryId)->update($validated);
        } else {
            VettasCategory::create($validated);
        }

        return redirect()->route('admin.vettas.categories')
            ->with('success', $this->isEditing
                ? 'Vettas category updated successfully.'
                : 'Vettas category created successfully.');
    }

    public function addHighlight(): void
    {
        $this->highlights[] = '';
    }

    public function removeHighlight(int $index): void
    {
        unset($this->highlights[$index]);
        $this->highlights = array_values($this->highlights);
    }

    public function addFaq(): void
    {
        $this->faqs[] = ['question' => '', 'answer' => ''];
    }

    public function removeFaq(int $index): void
    {
        unset($this->faqs[$index]);
        $this->faqs = array_values($this->faqs);
    }

    public function render()
    {
        return view('livewire.admin.vettas.category-form')
            ->layout('layouts.admin', [
                'header' => $this->isEditing ? 'Edit Vettas Category' : 'Create Vettas Category',
            ]);
    }
}
