<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Academy\Applications;
use App\Models\Career\CareerApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AcademyApplicationsDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_an_admin_can_open_the_academy_applications_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);

        $this->actingAs($admin)
            ->get(route('admin.academy.applications'))
            ->assertOk()
            ->assertSee('Glow FM Academy admissions')
            ->assertSee('Academy Applications');

        $this->actingAs($staff)
            ->get(route('admin.academy.applications'))
            ->assertForbidden();
    }

    public function test_the_academy_workspace_only_lists_academy_applications(): void
    {
        $academy = $this->application('GLW-ACD-001', 'Academy Student', 'academy');
        $career = $this->application('GLW-JOB-001', 'Career Applicant', 'job');

        $component = Livewire::test(Applications::class)
            ->assertSee($academy->full_name)
            ->assertDontSee($career->full_name);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $component->call('openApplication', $career->id);
    }

    public function test_the_careers_workspace_excludes_academy_applications(): void
    {
        $this->application('GLW-ACD-002', 'Hidden Academy Student', 'academy');
        $this->application('GLW-JOB-002', 'Visible Career Applicant', 'job');

        Livewire::test(\App\Livewire\Admin\Career\CareerApplications::class)
            ->assertDontSee('Hidden Academy Student')
            ->assertSee('Visible Career Applicant');
    }

    private function application(string $code, string $name, string $type): CareerApplication
    {
        return CareerApplication::create([
            'application_code' => $code,
            'application_type' => $type,
            'full_name' => $name,
            'email' => str($code)->lower().'@example.com',
            'status' => 'new',
        ]);
    }
}
