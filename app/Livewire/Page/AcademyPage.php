<?php

namespace App\Livewire\Page;

use App\Mail\CareerApplicationReceivedMail;
use App\Models\Career\CareerApplication;
use App\Support\Seo;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class AcademyPage extends Component
{
    use WithFileUploads;

    public string $full_name = '';
    public string $email = '';
    public string $phone = '';
    public string $location = '';
    public string $department = '';
    public string $education_level = '';
    public string $institution = '';
    public string $skills = '';
    public string $motivation = '';
    public string $contribution = '';
    public string $available_from = '';
    public string $availability = '';
    public string $commitment_length = '';
    public bool $consent = false;
    public $resume;

    protected function rules(): array
    {
        return [
            'full_name' => 'required|string|min:3|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:40',
            'location' => 'required|string|max:255',
            'department' => 'required|in:Presentation & On-Air,News & Journalism,Production & Audio,Digital Broadcasting',
            'education_level' => 'required|string|max:120',
            'institution' => 'nullable|string|max:180',
            'skills' => 'required|string|min:10|max:3000',
            'motivation' => 'required|string|min:30|max:5000',
            'contribution' => 'required|string|min:20|max:5000',
            'available_from' => 'required|date|after_or_equal:today',
            'availability' => 'required|in:Weekday mornings,Weekday afternoons,Weekday evenings,Weekends,Flexible',
            'commitment_length' => 'required|in:3 months,6 months,9 months,12 months',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'consent' => 'accepted',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        $code = $this->uniqueCode();
        $path = $this->resume?->store('private/careers/resumes', 'local');

        $application = CareerApplication::create([
            'application_code' => $code,
            'application_type' => 'academy',
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'department' => $this->department,
            'education_level' => $this->education_level,
            'institution' => $this->institution ?: null,
            'skills' => $this->skills,
            'motivation' => $this->motivation,
            'contribution' => $this->contribution,
            'available_from' => $this->available_from,
            'availability' => $this->availability,
            'commitment_length' => $this->commitment_length,
            'resume_path' => $path,
            'resume_original_name' => $this->resume?->getClientOriginalName(),
            'status' => 'new',
            'consent' => true,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        try {
            Mail::to($application->email)->send(new CareerApplicationReceivedMail($application));
        } catch (\Throwable $exception) {
            report($exception);
        }

        $this->reset();
        session()->flash('success', "Your Glow FM Academy application ({$code}) has been received. We will contact you about the next admission steps.");
    }

    private function uniqueCode(): string
    {
        do {
            $code = 'GLW-ACD-'.now()->format('ymd').'-'.Str::upper(Str::random(5));
        } while (CareerApplication::where('application_code', $code)->exists());

        return $code;
    }

    public function render()
    {
        $description = 'Practical broadcasting training at Glow FM Academy in Akure, with 3, 6, 9 and 12-month learning programmes in radio presentation, journalism, production and digital broadcasting.';

        return view('livewire.page.academy-page')->layout('layouts.app', [
            'title' => 'Glow FM Academy - Broadcasting Training in Akure',
            'meta_title' => 'Glow FM Academy | Broadcasting Training in Akure',
            'meta_description' => $description,
            'canonical_url' => route('academy'),
            'structured_data' => Seo::siteGraph([
                'title' => 'Glow FM Academy',
                'description' => $description,
                'url' => route('academy'),
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Glow FM Academy', 'url' => route('academy')],
                ],
            ]),
        ]);
    }
}
