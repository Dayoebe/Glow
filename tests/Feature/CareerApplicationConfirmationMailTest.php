<?php

namespace Tests\Feature;

use App\Livewire\Page\CareerDetail;
use App\Livewire\Page\ProgrammeApplication;
use App\Mail\CareerApplicationReceivedMail;
use App\Models\Career\CareerApplication;
use App\Models\Career\CareerPosition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CareerApplicationConfirmationMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_job_applicant_receives_an_on_site_application_confirmation(): void
    {
        Mail::fake();
        Storage::fake('local');
        $position = $this->position();

        Livewire::test(CareerDetail::class, ['slug' => $position->slug])
            ->set('full_name', 'Ada Applicant')
            ->set('email', 'ada@example.com')
            ->set('resume', UploadedFile::fake()->create('ada-cv.pdf', 100, 'application/pdf'))
            ->call('submitApplication')
            ->assertHasNoErrors();

        Mail::assertSent(CareerApplicationReceivedMail::class, function ($mail) {
            return $mail->hasTo('ada@example.com')
                && $mail->application->application_type === 'job'
                && str_contains($mail->render(), 'this opportunity requires on-site work')
                && str_contains($mail->render(), 'We therefore consider applicants who live in Akure')
                && str_contains($mail->render(), 'Remote work is not available unless the specific vacancy clearly states otherwise');
        });
    }

    public function test_a_marketer_receives_confirmation_without_the_on_site_restriction(): void
    {
        Mail::fake();
        $application = CareerApplication::create([
            'application_code' => 'GLW-MKT-MAIL',
            'application_type' => 'marketer',
            'engagement_type' => 'deal-by-deal',
            'work_mode' => 'remote',
            'full_name' => 'Tola Partner',
            'email' => 'tola@example.com',
            'status' => 'new',
        ]);

        $mail = new CareerApplicationReceivedMail($application);
        $html = $mail->render();

        $this->assertStringContainsString('Advertiser/Marketer work arrangement', $html);
        $this->assertStringContainsString('on-site, remotely, or through a hybrid arrangement', $html);
        $this->assertStringNotContainsString('We therefore consider applicants who live in Akure', $html);
    }

    public function test_programme_submission_sends_the_same_confirmation(): void
    {
        Mail::fake();
        Storage::fake('local');

        Livewire::test(ProgrammeApplication::class, ['type' => 'internship'])
            ->set('full_name', 'Bola Intern')
            ->set('email', 'bola@example.com')
            ->set('phone', '08012345678')
            ->set('location', 'Akure')
            ->set('department', 'Programming & Production')
            ->set('education_level', 'Bachelor degree')
            ->set('skills', 'Audio editing, research and production support.')
            ->set('motivation', 'I want to develop practical broadcasting skills with the Glow FM team.')
            ->set('contribution', 'I will bring reliable production support, research and creative ideas.')
            ->set('available_from', now()->addWeek()->toDateString())
            ->set('availability', 'Weekdays - full time')
            ->set('commitment_length', '3-6 months')
            ->set('resume', UploadedFile::fake()->create('bola-cv.pdf', 100, 'application/pdf'))
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors();

        Mail::assertSent(CareerApplicationReceivedMail::class, fn ($mail) =>
            $mail->hasTo('bola@example.com') && $mail->application->application_type === 'internship'
        );
    }

    private function position(): CareerPosition
    {
        return CareerPosition::create([
            'title' => 'Studio Producer',
            'slug' => 'studio-producer-mail-test',
            'description' => 'Produce daily radio shows for Glow FM.',
            'employment_type' => 'full-time',
            'workplace_type' => 'onsite',
            'experience_level' => 'mid',
            'is_published' => true,
            'allow_applications' => true,
            'status' => 'open',
            'published_at' => now()->subDay(),
        ]);
    }
}
