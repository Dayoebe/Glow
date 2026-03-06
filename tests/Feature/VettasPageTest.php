<?php

namespace Tests\Feature;

use App\Models\Vettas\VettasCategory;
use App\Models\Vettas\VettasPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VettasPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_vettas_page_only_shows_published_photos(): void
    {
        $category = VettasCategory::factory()->create([
            'name' => 'Studio Sessions',
            'slug' => 'studio-sessions',
        ]);

        VettasPhoto::factory()->create([
            'category_id' => $category->id,
            'title' => 'Published Studio Shot',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        VettasPhoto::factory()->draft()->create([
            'category_id' => $category->id,
            'title' => 'Draft Studio Shot',
        ]);

        $this->get(route('vettas.index'))
            ->assertOk()
            ->assertSee('Published Studio Shot')
            ->assertDontSee('Draft Studio Shot');
    }

    public function test_vettas_page_can_filter_by_category(): void
    {
        $firstCategory = VettasCategory::factory()->create([
            'name' => 'Backstage',
            'slug' => 'backstage',
        ]);
        $secondCategory = VettasCategory::factory()->create([
            'name' => 'Events',
            'slug' => 'events',
        ]);

        VettasPhoto::factory()->create([
            'category_id' => $firstCategory->id,
            'title' => 'Backstage Energy',
        ]);

        VettasPhoto::factory()->create([
            'category_id' => $secondCategory->id,
            'title' => 'Event Spotlight',
        ]);

        $this->get(route('vettas.index', ['category' => $firstCategory->slug]))
            ->assertOk()
            ->assertSee('Backstage Energy')
            ->assertDontSee('Event Spotlight');
    }
}
