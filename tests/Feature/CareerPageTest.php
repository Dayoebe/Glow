<?php

namespace Tests\Feature;

use App\Models\Career\CareerPosition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareerPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_careers_page_only_shows_positions_accepting_applications(): void
    {
        $this->createCareerPosition([
            'title' => 'On Air Producer',
            'slug' => 'on-air-producer',
        ]);

        $this->createCareerPosition([
            'title' => 'Closed Presenter Role',
            'slug' => 'closed-presenter-role',
            'status' => 'closed',
        ]);

        $this->createCareerPosition([
            'title' => 'Expired Marketing Role',
            'slug' => 'expired-marketing-role',
            'application_deadline' => now()->subDay()->toDateString(),
        ]);

        $this->get(route('careers.index'))
            ->assertOk()
            ->assertSee('On Air Producer')
            ->assertDontSee('Closed Presenter Role')
            ->assertDontSee('Expired Marketing Role');
    }

    public function test_closed_career_position_detail_page_is_not_public(): void
    {
        $this->createCareerPosition([
            'title' => 'Closed Presenter Role',
            'slug' => 'closed-presenter-role',
            'status' => 'closed',
        ]);

        $this->get(route('careers.show', 'closed-presenter-role'))
            ->assertNotFound();
    }

    private function createCareerPosition(array $overrides = []): CareerPosition
    {
        return CareerPosition::create(array_merge([
            'title' => 'Studio Producer',
            'slug' => 'studio-producer',
            'excerpt' => 'Help produce daily radio shows.',
            'description' => 'Help produce daily radio shows for Glow FM.',
            'department' => 'Production',
            'employment_type' => 'full-time',
            'workplace_type' => 'onsite',
            'experience_level' => 'mid',
            'is_published' => true,
            'allow_applications' => true,
            'status' => 'open',
            'published_at' => now()->subDay(),
        ], $overrides));
    }
}
