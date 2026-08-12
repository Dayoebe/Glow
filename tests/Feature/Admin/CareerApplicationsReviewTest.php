<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Career\CareerApplications;
use App\Models\Career\CareerApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CareerApplicationsReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_applicant_status_can_be_changed_from_the_review(): void
    {
        $reviewer = User::factory()->create();
        $application = $this->application('APP-REVIEW', 'Review Candidate', 'new');

        $this->actingAs($reviewer);

        Livewire::test(CareerApplications::class)
            ->call('openApplication', $application->id)
            ->assertSee('Application status')
            ->call('setStatus', $application->id, 'reviewing')
            ->assertSee('Current: Reviewing')
            ->assertSee('Application moved to Reviewing.');

        $this->assertDatabaseHas('career_applications', [
            'id' => $application->id,
            'status' => 'reviewing',
            'reviewed_by' => $reviewer->id,
        ]);
        $this->assertNotNull($application->fresh()->reviewed_at);
    }

    public function test_applicants_can_be_filtered_and_sorted_by_workflow_status(): void
    {
        $this->application('APP-HIRED', 'Hired Candidate', 'hired');
        $this->application('APP-NEW', 'New Candidate', 'new');
        $this->application('APP-REVIEWING', 'Reviewing Candidate', 'reviewing');

        Livewire::test(CareerApplications::class)
            ->set('sortBy', 'status_pipeline')
            ->assertSeeInOrder(['New Candidate', 'Reviewing Candidate', 'Hired Candidate'])
            ->set('filterStatus', 'reviewing')
            ->assertSee('Reviewing Candidate')
            ->assertDontSee('New Candidate')
            ->assertDontSee('Hired Candidate');
    }

    private function application(string $code, string $name, string $status): CareerApplication
    {
        return CareerApplication::create([
            'application_code' => $code,
            'application_type' => 'internship',
            'full_name' => $name,
            'email' => str($code)->lower().'@example.com',
            'status' => $status,
        ]);
    }
}
