<?php

namespace Tests\Feature;

use App\Livewire\Page\ProgrammeApplication;
use App\Models\Career\CareerApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProgrammeApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_internship_and_volunteer_forms_are_public(): void
    {
        $this->get(route('careers.programmes.apply', 'internship'))->assertOk()->assertSee('Internship application');
        $this->get(route('careers.programmes.apply', 'volunteer'))->assertOk()->assertSee('Volunteer application');
        $this->get(route('careers.programmes.apply', 'marketer'))->assertOk()->assertSee('Advertiser/Marketer application');
        $this->get('/careers/apply/unknown')->assertNotFound();
    }

    public function test_a_deal_by_deal_marketer_can_register_without_a_resume(): void
    {
        Livewire::test(ProgrammeApplication::class, ['type' => 'marketer'])
            ->set('full_name', 'Tola Partner')
            ->set('email', 'tola@example.com')
            ->set('phone', '08055555555')
            ->set('location', 'Akure')
            ->set('engagement_type', 'deal-by-deal')
            ->set('work_mode', 'remote')
            ->set('sales_experience', '1-2 years')
            ->set('client_network', 'I work with local retailers and event organisers in Ondo State.')
            ->set('services_to_promote', 'Radio adverts, sponsored programmes and event partnerships.')
            ->set('skills', 'Prospecting, relationship management and business negotiation.')
            ->set('motivation', 'I want to connect suitable local businesses with the Glow FM audience.')
            ->set('contribution', 'I will identify qualified leads, present offers clearly and follow through.')
            ->set('available_from', now()->addWeek()->toDateString())
            ->set('availability', 'Flexible / shift based')
            ->set('commitment_length', 'Ongoing / flexible')
            ->set('consent', true)
            ->set('commission_acknowledged', true)
            ->call('submit')
            ->assertHasNoErrors();

        $application = CareerApplication::firstOrFail();
        $this->assertSame('marketer', $application->application_type);
        $this->assertSame('deal-by-deal', $application->engagement_type);
        $this->assertSame('remote', $application->work_mode);
        $this->assertTrue($application->commission_acknowledged);
        $this->assertNull($application->resume_path);
    }

    public function test_an_internship_application_can_be_submitted(): void
    {
        Storage::fake('local');

        Livewire::test(ProgrammeApplication::class, ['type' => 'internship'])
            ->set('full_name', 'Ada Applicant')
            ->set('email', 'ada@example.com')
            ->set('phone', '08012345678')
            ->set('location', 'Lagos')
            ->set('department', 'Programming & Production')
            ->set('education_level', 'HND/Bachelor degree')
            ->set('skills', str_repeat('Production and editing. ', 2))
            ->set('motivation', str_repeat('I want to learn and contribute. ', 2))
            ->set('contribution', str_repeat('I offer creativity and reliability. ', 2))
            ->set('available_from', now()->addWeek()->toDateString())
            ->set('availability', 'Weekdays - full time')
            ->set('commitment_length', '3-6 months')
            ->set('resume', UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'))
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors();

        $application = CareerApplication::firstOrFail();
        $this->assertSame('internship', $application->application_type);
        $this->assertNull($application->career_position_id);
        Storage::disk('local')->assertExists($application->resume_path);
    }
}
