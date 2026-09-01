<?php

namespace Tests\Feature;

use App\Livewire\Page\AcademyPage;
use App\Mail\CareerApplicationReceivedMail;
use App\Models\Career\CareerApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class AcademyPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_academy_page_is_public_and_explains_the_programme(): void
    {
        $this->get(route('academy'))
            ->assertOk()
            ->assertSee('Glow FM Academy')
            ->assertSee('3 months')
            ->assertSee('12 months')
            ->assertSee('What you will learn');
    }

    public function test_a_valid_academy_application_is_stored_and_acknowledged(): void
    {
        Mail::fake();

        Livewire::test(AcademyPage::class)
            ->set('full_name', 'Kemi Broadcaster')
            ->set('email', 'kemi@example.com')
            ->set('phone', '08012345678')
            ->set('location', 'Akure')
            ->set('department', 'Presentation & On-Air')
            ->set('education_level', 'HND/Bachelor degree')
            ->set('skills', 'I present at school events and enjoy public speaking.')
            ->set('motivation', 'I want professional guidance and practical experience inside a working radio station.')
            ->set('contribution', 'I want to confidently plan and present a complete radio programme.')
            ->set('available_from', now()->addWeek()->toDateString())
            ->set('availability', 'Weekends')
            ->set('commitment_length', '6 months')
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSee('has been received');

        $application = CareerApplication::firstOrFail();
        $this->assertSame('academy', $application->application_type);
        $this->assertSame('6 months', $application->commitment_length);
        $this->assertStringStartsWith('GLW-ACD-', $application->application_code);
        Mail::assertSent(CareerApplicationReceivedMail::class, fn ($mail) => $mail->hasTo('kemi@example.com'));
    }

    public function test_academy_application_requires_a_supported_duration_and_consent(): void
    {
        Livewire::test(AcademyPage::class)
            ->set('commitment_length', '2 months')
            ->set('consent', false)
            ->call('submit')
            ->assertHasErrors(['commitment_length', 'consent']);
    }
}
