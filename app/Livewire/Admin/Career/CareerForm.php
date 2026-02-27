<?php

namespace App\Livewire\Admin\Career;

use App\Models\Career\CareerPosition;
use Illuminate\Support\Str;
use Livewire\Component;

class CareerForm extends Component
{
    public ?CareerPosition $position = null;
    public bool $isEditing = false;

    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $description = '';
    public $responsibilities = '';
    public $requirements = '';
    public $benefits = '';
    public $department = '';
    public $employment_type = 'full-time';
    public $workplace_type = 'onsite';
    public $experience_level = 'mid';
    public $location = '';
    public $city = '';
    public $state = '';
    public $country = '';
    public $min_salary = '';
    public $max_salary = '';
    public $salary_currency = 'NGN';
    public $salary_period = 'monthly';
    public $application_deadline = '';
    public $start_date = '';
    public $positions_available = 1;
    public $status = 'open';
    public $is_featured = false;
    public $is_published = false;
    public $allow_applications = true;
    public $published_at = '';

    public bool $manualSlug = false;

    protected function rules(): array
    {
        $rules = [
            'title' => 'required|string|min:5|max:255',
            'slug' => 'nullable|string|max:255|unique:career_positions,slug',
            'excerpt' => 'nullable|string|max:700',
            'description' => 'required|string|min:80',
            'responsibilities' => 'nullable|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'department' => 'nullable|string|max:120',
            'employment_type' => 'required|in:full-time,part-time,contract,internship,freelance,temporary',
            'workplace_type' => 'required|in:onsite,hybrid,remote',
            'experience_level' => 'required|in:entry,junior,mid,senior,lead,executive',
            'location' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:120',
            'state' => 'nullable|string|max:120',
            'country' => 'nullable|string|max:120',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|gte:min_salary',
            'salary_currency' => 'required|string|max:10',
            'salary_period' => 'required|in:hourly,monthly,yearly',
            'application_deadline' => 'nullable|date',
            'start_date' => 'nullable|date',
            'positions_available' => 'required|integer|min:1|max:1000',
            'status' => 'required|in:open,closed,paused',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'allow_applications' => 'boolean',
            'published_at' => 'nullable|date',
        ];

        if ($this->isEditing && $this->position) {
            $rules['slug'] = 'nullable|string|max:255|unique:career_positions,slug,' . $this->position->id;
        }

        return $rules;
    }

    public function mount($id = null): void
    {
        if ($id) {
            $this->position = CareerPosition::findOrFail($id);
            $this->isEditing = true;
            $this->loadPositionData();
            return;
        }

        $this->published_at = now()->format('Y-m-d\TH:i');
    }

    private function loadPositionData(): void
    {
        $this->title = $this->position->title;
        $this->slug = $this->position->slug;
        $this->excerpt = (string) $this->position->excerpt;
        $this->description = $this->position->description;
        $this->responsibilities = (string) $this->position->responsibilities;
        $this->requirements = (string) $this->position->requirements;
        $this->benefits = (string) $this->position->benefits;
        $this->department = (string) $this->position->department;
        $this->employment_type = $this->position->employment_type;
        $this->workplace_type = $this->position->workplace_type;
        $this->experience_level = $this->position->experience_level;
        $this->location = (string) $this->position->location;
        $this->city = (string) $this->position->city;
        $this->state = (string) $this->position->state;
        $this->country = (string) $this->position->country;
        $this->min_salary = $this->position->min_salary;
        $this->max_salary = $this->position->max_salary;
        $this->salary_currency = $this->position->salary_currency;
        $this->salary_period = $this->position->salary_period;
        $this->application_deadline = $this->position->application_deadline?->format('Y-m-d') ?: '';
        $this->start_date = $this->position->start_date?->format('Y-m-d') ?: '';
        $this->positions_available = $this->position->positions_available;
        $this->status = $this->position->status;
        $this->is_featured = $this->position->is_featured;
        $this->is_published = $this->position->is_published;
        $this->allow_applications = $this->position->allow_applications;
        $this->published_at = $this->position->published_at?->format('Y-m-d\TH:i') ?: '';
    }

    public function updatedTitle(string $value): void
    {
        if (!$this->manualSlug) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedSlug(): void
    {
        $this->manualSlug = true;
    }

    public function saveAsDraft()
    {
        $this->is_published = false;
        return $this->save(false);
    }

    public function publishNow()
    {
        return $this->save(true);
    }

    public function update()
    {
        return $this->save(false);
    }

    private function save(bool $publishNow = false)
    {
        if ($publishNow) {
            $this->is_published = true;
            if (empty($this->published_at)) {
                $this->published_at = now()->format('Y-m-d\TH:i');
            }
        }

        $this->validate();

        $data = $this->prepareData();

        if ($this->isEditing && $this->position) {
            $this->position->update($data);
            session()->flash('success', 'Career position updated successfully.');
        } else {
            $data['created_by'] = auth()->id();
            CareerPosition::create($data);
            session()->flash('success', $this->is_published
                ? 'Career position published successfully.'
                : 'Career position saved as draft.');
        }

        return redirect()->route('admin.careers.index');
    }

    private function prepareData(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug ?: Str::slug($this->title),
            'excerpt' => $this->excerpt ?: null,
            'description' => $this->description,
            'responsibilities' => $this->responsibilities ?: null,
            'requirements' => $this->requirements ?: null,
            'benefits' => $this->benefits ?: null,
            'department' => $this->department ?: null,
            'employment_type' => $this->employment_type,
            'workplace_type' => $this->workplace_type,
            'experience_level' => $this->experience_level,
            'location' => $this->location ?: null,
            'city' => $this->city ?: null,
            'state' => $this->state ?: null,
            'country' => $this->country ?: null,
            'min_salary' => $this->min_salary === '' ? null : $this->min_salary,
            'max_salary' => $this->max_salary === '' ? null : $this->max_salary,
            'salary_currency' => strtoupper($this->salary_currency ?: 'NGN'),
            'salary_period' => $this->salary_period,
            'application_deadline' => $this->application_deadline ?: null,
            'start_date' => $this->start_date ?: null,
            'positions_available' => $this->positions_available ?: 1,
            'status' => $this->status,
            'is_featured' => (bool) $this->is_featured,
            'is_published' => (bool) $this->is_published,
            'allow_applications' => (bool) $this->allow_applications,
            'published_at' => $this->is_published ? ($this->published_at ?: now()) : null,
            'updated_by' => auth()->id(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.career.' . ($this->isEditing ? 'edit' : 'create'))
            ->layout('layouts.admin', [
                'header' => $this->isEditing ? 'Edit Career Position' : 'Create Career Position',
            ]);
    }
}
