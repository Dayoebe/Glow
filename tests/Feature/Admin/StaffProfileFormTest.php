<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Profile\ProfileForm;
use App\Models\Staff\StaffMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class StaffProfileFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_linked_staff_can_update_their_professional_profile(): void
    {
        $user = User::factory()->create(['name' => 'Old Name', 'role' => 'staff', 'is_active' => true]);
        $staff = StaffMember::query()->create([
            'user_id' => $user->id, 'name' => $user->name, 'slug' => 'old-name',
            'role' => 'Presenter', 'email' => $user->email, 'is_active' => true,
        ]);

        Livewire::actingAs($user)->test(ProfileForm::class)
            ->assertSet('hasStaffProfile', true)
            ->set('name', 'Updated Presenter')
            ->set('email', 'presenter@example.com')
            ->set('bio', 'Presenter, producer and community storyteller at Glow FM.')
            ->set('phone', '+234 800 123 4567')
            ->set('avatar', 'https://example.com/presenter.jpg')
            ->set('social_links.instagram', 'https://instagram.com/presenter')
            ->set('social_links.linkedin', 'https://linkedin.com/in/presenter')
            ->set('profile_visibility.phone', true)
            ->set('profile_visibility.social.instagram', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Profile updated successfully.');

        $user->refresh();
        $staff->refresh();
        $this->assertSame('Updated Presenter', $user->name);
        $this->assertSame('presenter@example.com', $staff->email);
        $this->assertSame('+234 800 123 4567', $staff->phone);
        $this->assertSame('https://example.com/presenter.jpg', $staff->photo_url);
        $this->assertSame('https://instagram.com/presenter', $staff->social_links['instagram']);
        $this->assertFalse($staff->isPubliclyVisible('email'));
        $this->assertTrue($staff->isPubliclyVisible('phone'));

        $this->get(route('staff.show', $staff->slug))
            ->assertOk()
            ->assertSee('Presenter, producer and community storyteller')
            ->assertSee('+234 800 123 4567')
            ->assertSee('https://instagram.com/presenter', false)
            ->assertDontSee('presenter@example.com')
            ->assertDontSee('https://linkedin.com/in/presenter', false);
    }

    public function test_staff_can_upload_and_preview_a_profile_picture(): void
    {
        Storage::fake('public');
        config(['cloudinary.cloud_url' => null, 'services.cloudinary.url' => null]);
        $user = User::factory()->create(['role' => 'staff', 'is_active' => true]);
        StaffMember::query()->create([
            'user_id' => $user->id, 'name' => $user->name, 'slug' => 'photo-staff',
            'email' => $user->email, 'is_active' => true,
        ]);

        Livewire::actingAs($user)->test(ProfileForm::class)
            ->set('avatar_upload', UploadedFile::fake()->image('profile.jpg', 600, 600))
            ->call('save')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertStringContainsString('/storage/uploads/users/avatars/', $user->avatar);
        $this->assertSame($user->avatar, $user->staffMember->photo_url);
    }

    public function test_unlinked_account_sees_a_clear_staff_profile_notice(): void
    {
        $user = User::factory()->create(['role' => 'staff', 'is_active' => true]);

        Livewire::actingAs($user)->test(ProfileForm::class)
            ->assertSet('hasStaffProfile', false)
            ->assertSee('Public staff profile not linked');
    }

    public function test_contact_details_and_social_links_are_private_by_default(): void
    {
        $user = User::factory()->create(['role' => 'staff', 'is_active' => true]);
        $staff = StaffMember::query()->create([
            'user_id' => $user->id, 'name' => 'Private Staff', 'slug' => 'private-staff',
            'email' => 'private@example.com', 'phone' => '+234 900 000 0000',
            'social_links' => ['facebook' => 'https://facebook.com/private-staff'],
            'is_active' => true,
        ]);

        $this->get(route('staff.show', $staff->slug))
            ->assertOk()
            ->assertDontSee('private@example.com')
            ->assertDontSee('+234 900 000 0000')
            ->assertDontSee('https://facebook.com/private-staff', false);
    }
}
