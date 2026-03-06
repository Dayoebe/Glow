<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Vettas\PhotoForm;
use App\Models\User;
use App\Models\Vettas\VettasCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VettasAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_vettas_dashboard_page(): void
    {
        $admin = $this->makeAdminUser();

        $this->actingAs($admin)
            ->get(route('admin.vettas.index'))
            ->assertOk()
            ->assertSee('Add Photo')
            ->assertSee('Total Photos');
    }

    public function test_admin_can_create_a_vettas_photo_from_livewire_form(): void
    {
        Storage::fake('public');

        $admin = $this->makeAdminUser();
        $category = VettasCategory::factory()->create();

        Livewire::actingAs($admin)
            ->test(PhotoForm::class)
            ->set('title', 'Studio Lights')
            ->set('caption', 'A fresh look inside the studio.')
            ->set('category_id', (string) $category->id)
            ->set('photographer_name', 'Glow FM Media')
            ->set('image', UploadedFile::fake()->image('studio-lights.jpg'))
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.vettas.index'));

        $this->assertDatabaseHas('vettas_photos', [
            'title' => 'Studio Lights',
            'category_id' => $category->id,
            'is_published' => true,
        ]);
    }

    private function makeAdminUser(): User
    {
        Role::findOrCreate('admin', 'web');

        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $user->assignRole('admin');

        return $user;
    }
}
