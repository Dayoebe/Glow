<?php

namespace App\Livewire\Page;

use App\Models\Career\CareerApplication;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProgrammeApplication extends Component
{
    use WithFileUploads;

    public string $type;
    public string $full_name = '';
    public string $email = '';
    public string $phone = '';
    public string $location = '';
    public string $department = '';
    public string $education_level = '';
    public string $institution = '';
    public string $course_of_study = '';
    public string $skills = '';
    public string $motivation = '';
    public string $contribution = '';
    public string $linkedin_url = '';
    public string $portfolio_url = '';
    public string $available_from = '';
    public string $availability = '';
    public string $commitment_length = '';
    public bool $consent = false;
    public $resume;

    public function mount(string $type): void
    {
        abort_unless(in_array($type, ['internship', 'volunteer'], true), 404);
        $this->type = $type;
    }

    protected function rules(): array
    {
        return [
            'full_name' => 'required|string|min:3|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:40',
            'location' => 'required|string|max:255',
            'department' => 'required|string|max:120',
            'education_level' => 'required|string|max:120',
            'institution' => 'nullable|string|max:180',
            'course_of_study' => 'nullable|string|max:180',
            'skills' => 'required|string|min:20|max:3000',
            'motivation' => 'required|string|min:30|max:5000',
            'contribution' => 'required|string|min:30|max:5000',
            'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'available_from' => 'required|date|after_or_equal:today',
            'availability' => 'required|string|max:120',
            'commitment_length' => 'required|string|max:120',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'consent' => 'accepted',
        ];
    }

    public function submit(): void
    {
        $this->validate();
        $code = $this->uniqueCode();
        $path = $this->resume->store('private/careers/resumes', 'local');

        CareerApplication::create([
            'career_position_id' => null,
            'application_code' => $code,
            'application_type' => $this->type,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'department' => $this->department,
            'education_level' => $this->education_level,
            'institution' => $this->institution ?: null,
            'course_of_study' => $this->course_of_study ?: null,
            'skills' => $this->skills,
            'motivation' => $this->motivation,
            'contribution' => $this->contribution,
            'linkedin_url' => $this->linkedin_url ?: null,
            'portfolio_url' => $this->portfolio_url ?: null,
            'available_from' => $this->available_from,
            'availability' => $this->availability,
            'commitment_length' => $this->commitment_length,
            'resume_path' => $path,
            'resume_original_name' => $this->resume->getClientOriginalName(),
            'status' => 'new',
            'consent' => true,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->resetExcept('type');
        session()->flash('success', "Thank you. Your {$this->type} application ({$code}) has been received.");
    }

    private function uniqueCode(): string
    {
        do {
            $prefix = $this->type === 'internship' ? 'INT' : 'VOL';
            $code = "GLW-{$prefix}-" . now()->format('ymd') . '-' . Str::upper(Str::random(5));
        } while (CareerApplication::where('application_code', $code)->exists());

        return $code;
    }

    public function render()
    {
        $label = $this->type === 'internship' ? 'Internship' : 'Volunteer';

        return view('livewire.page.programme-application', compact('label'))
            ->layout('layouts.app', ['title' => "{$label} Application - Glow FM"]);
    }
}
