<?php

namespace App\Livewire\Page;

use App\Models\Career\CareerApplication;
use App\Models\Career\CareerPosition;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CareerDetail extends Component
{
    use WithFileUploads;

    public CareerPosition $position;

    public $full_name = '';
    public $email = '';
    public $phone = '';
    public $location = '';
    public $linkedin_url = '';
    public $portfolio_url = '';
    public $years_experience = '';
    public $current_company = '';
    public $current_role = '';
    public $expected_salary = '';
    public $available_from = '';
    public $cover_letter = '';
    public $resume;

    protected function rules()
    {
        return [
            'full_name' => 'required|string|min:3|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:40',
            'location' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'years_experience' => 'nullable|integer|min:0|max:60',
            'current_company' => 'nullable|string|max:150',
            'current_role' => 'nullable|string|max:150',
            'expected_salary' => 'nullable|numeric|min:0',
            'available_from' => 'nullable|date',
            'cover_letter' => 'nullable|string|min:30|max:8000',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ];
    }

    public function mount(string $slug)
    {
        $this->position = CareerPosition::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function submitApplication()
    {
        if (!$this->position->isAcceptingApplications()) {
            session()->flash('error', 'This position is no longer accepting applications.');
            return;
        }

        $this->validate();

        $resumePath = $this->resume->store('uploads/careers/resumes', 'public');

        $applicationCode = $this->generateApplicationCode();

        CareerApplication::create([
            'career_position_id' => $this->position->id,
            'application_code' => $applicationCode,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'location' => $this->location ?: null,
            'linkedin_url' => $this->linkedin_url ?: null,
            'portfolio_url' => $this->portfolio_url ?: null,
            'years_experience' => $this->years_experience === '' ? null : (int) $this->years_experience,
            'current_company' => $this->current_company ?: null,
            'current_role' => $this->current_role ?: null,
            'expected_salary' => $this->expected_salary === '' ? null : (float) $this->expected_salary,
            'available_from' => $this->available_from ?: null,
            'cover_letter' => $this->cover_letter ?: null,
            'resume_path' => $resumePath,
            'resume_original_name' => $this->resume->getClientOriginalName(),
            'status' => 'new',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->reset([
            'full_name',
            'email',
            'phone',
            'location',
            'linkedin_url',
            'portfolio_url',
            'years_experience',
            'current_company',
            'current_role',
            'expected_salary',
            'available_from',
            'cover_letter',
            'resume',
        ]);

        session()->flash('success', 'Application submitted successfully. We will contact you if shortlisted.');
    }

    private function generateApplicationCode(): string
    {
        do {
            $code = 'GLW-' . now()->format('ymd') . '-' . Str::upper(Str::random(6));
        } while (CareerApplication::where('application_code', $code)->exists());

        return $code;
    }

    public function getRelatedPositionsProperty()
    {
        return CareerPosition::query()
            ->published()
            ->where('id', '!=', $this->position->id)
            ->latest('published_at')
            ->take(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.page.career-detail', [
            'relatedPositions' => $this->relatedPositions,
        ])->layout('layouts.app', [
            'title' => $this->position->title . ' - Careers - Glow FM',
            'meta_title' => $this->position->title . ' - Careers - Glow FM',
            'meta_description' => Str::limit(strip_tags($this->position->excerpt ?: $this->position->description), 180),
        ]);
    }
}
