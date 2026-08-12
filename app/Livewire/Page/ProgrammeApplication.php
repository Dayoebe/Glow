<?php

namespace App\Livewire\Page;

use App\Mail\CareerApplicationReceivedMail;
use App\Models\Career\CareerApplication;
use Illuminate\Support\Facades\Mail;
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
    public string $engagement_type = '';
    public string $work_mode = '';
    public string $sales_experience = '';
    public string $client_network = '';
    public string $services_to_promote = '';
    public string $first_lead = '';
    public string $motivation = '';
    public string $contribution = '';
    public string $linkedin_url = '';
    public string $portfolio_url = '';
    public string $available_from = '';
    public string $availability = '';
    public string $commitment_length = '';
    public bool $consent = false;
    public bool $commission_acknowledged = false;
    public $resume;

    public function mount(string $type): void
    {
        abort_unless(in_array($type, ['internship', 'volunteer', 'marketer'], true), 404);
        $this->type = $type;
    }

    protected function rules(): array
    {
        return [
            'full_name' => 'required|string|min:3|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:40',
            'location' => 'required|string|max:255',
            'department' => $this->type === 'marketer' ? 'nullable|string|max:120' : 'required|string|max:120',
            'education_level' => $this->type === 'marketer' ? 'nullable|string|max:120' : 'required|string|max:120',
            'institution' => 'nullable|string|max:180',
            'course_of_study' => 'nullable|string|max:180',
            'skills' => 'required|string|min:20|max:3000',
            'motivation' => 'required|string|min:30|max:5000',
            'contribution' => 'required|string|min:30|max:5000',
            'engagement_type' => $this->type === 'marketer' ? 'required|in:permanent,deal-by-deal' : 'nullable',
            'work_mode' => $this->type === 'marketer' ? 'required|in:onsite,remote,hybrid' : 'nullable',
            'sales_experience' => $this->type === 'marketer' ? 'required|string|max:120' : 'nullable',
            'client_network' => $this->type === 'marketer' ? 'required|string|min:20|max:3000' : 'nullable',
            'services_to_promote' => $this->type === 'marketer' ? 'required|string|min:10|max:3000' : 'nullable',
            'first_lead' => $this->type === 'marketer' ? 'nullable|string|max:3000' : 'nullable',
            'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'available_from' => 'required|date|after_or_equal:today',
            'availability' => 'required|string|max:120',
            'commitment_length' => 'required|string|max:120',
            'resume' => ($this->type === 'marketer' && $this->engagement_type === 'deal-by-deal' ? 'nullable' : 'required').'|file|mimes:pdf,doc,docx|max:5120',
            'consent' => 'accepted',
            'commission_acknowledged' => $this->type === 'marketer' ? 'accepted' : 'nullable',
        ];
    }

    public function submit(): void
    {
        $this->validate();
        $code = $this->uniqueCode();
        $path = $this->resume?->store('private/careers/resumes', 'local');

        $application = CareerApplication::create([
            'career_position_id' => null,
            'application_code' => $code,
            'application_type' => $this->type,
            'engagement_type' => $this->engagement_type ?: null,
            'work_mode' => $this->work_mode ?: null,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'department' => $this->department,
            'education_level' => $this->education_level,
            'institution' => $this->institution ?: null,
            'course_of_study' => $this->course_of_study ?: null,
            'skills' => $this->skills,
            'sales_experience' => $this->sales_experience ?: null,
            'client_network' => $this->client_network ?: null,
            'services_to_promote' => $this->services_to_promote ?: null,
            'first_lead' => $this->first_lead ?: null,
            'motivation' => $this->motivation,
            'contribution' => $this->contribution,
            'linkedin_url' => $this->linkedin_url ?: null,
            'portfolio_url' => $this->portfolio_url ?: null,
            'available_from' => $this->available_from,
            'availability' => $this->availability,
            'commitment_length' => $this->commitment_length,
            'resume_path' => $path,
            'resume_original_name' => $this->resume?->getClientOriginalName(),
            'status' => 'new',
            'consent' => true,
            'commission_acknowledged' => $this->type === 'marketer',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        try {
            Mail::to($application->email)->send(new CareerApplicationReceivedMail($application));
        } catch (\Throwable $exception) {
            report($exception);
        }

        $this->resetExcept('type');
        session()->flash('success', "Thank you. Your {$this->type} application ({$code}) has been received.");
    }

    private function uniqueCode(): string
    {
        do {
            $prefix = match ($this->type) {
                'internship' => 'INT',
                'volunteer' => 'VOL',
                default => 'MKT',
            };
            $code = "GLW-{$prefix}-" . now()->format('ymd') . '-' . Str::upper(Str::random(5));
        } while (CareerApplication::where('application_code', $code)->exists());

        return $code;
    }

    public function render()
    {
        $label = match ($this->type) {
            'internship' => 'Internship',
            'volunteer' => 'Volunteer',
            default => 'Advertiser/Marketer',
        };

        return view('livewire.page.programme-application', compact('label'))
            ->layout('layouts.app', ['title' => "{$label} Application - Glow FM"]);
    }
}
