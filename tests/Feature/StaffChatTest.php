<?php

namespace Tests\Feature;

use App\Livewire\Admin\Chat\ChatHub;
use App\Models\Chat\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StaffChatTest extends TestCase
{
    use RefreshDatabase;

    private function staff(array $attributes = []): User
    {
        return User::factory()->create(array_merge(['role' => 'staff', 'is_active' => true], $attributes));
    }

    public function test_active_staff_can_open_chat_but_regular_users_cannot(): void
    {
        $staff = $this->staff();
        $listener = User::factory()->create(['role' => 'user', 'is_active' => true]);

        $this->actingAs($staff)->get(route('admin.chat'))->assertOk()->assertSee('Staff Chat');
        $this->actingAs($listener)->get(route('admin.chat'))->assertRedirect(route('home'));
    }

    public function test_staff_can_start_a_direct_conversation_and_send_a_timestamped_message(): void
    {
        $sender = $this->staff(['name' => 'Dayo Sender']);
        $recipient = $this->staff(['name' => 'Tola Receiver']);

        Livewire::actingAs($sender)->test(ChatHub::class)
            ->set('directRecipientId', $recipient->id)
            ->call('startDirect')
            ->set('messageBody', 'Studio B is ready for handover.')
            ->call('sendMessage')
            ->assertHasNoErrors();

        $conversation = Conversation::where('type', 'direct')->firstOrFail();
        $this->assertEqualsCanonicalizing([$sender->id, $recipient->id], $conversation->participants()->pluck('users.id')->all());
        $this->assertDatabaseHas('chat_messages', ['conversation_id' => $conversation->id, 'sender_id' => $sender->id, 'body' => 'Studio B is ready for handover.']);
        $this->assertNotNull($conversation->fresh()->last_message_at);
    }

    public function test_targeted_broadcast_is_visible_only_to_selected_staff(): void
    {
        $sender = $this->staff(['name' => 'Admin Sender', 'role' => 'admin']);
        $selected = $this->staff(['name' => 'Selected Staff']);
        $excluded = $this->staff(['name' => 'Excluded Staff']);

        Livewire::actingAs($sender)->test(ChatHub::class)
            ->set('broadcastTitle', 'Urgent production update')
            ->set('broadcastBody', 'Please report to Studio A by 2 PM.')
            ->set('broadcastPriority', 'urgent')
            ->set('broadcastPinned', true)
            ->set('broadcastEveryone', false)
            ->set('broadcastRecipientIds', [$selected->id, $excluded->id + 999])
            ->call('createBroadcast')
            ->assertHasNoErrors();

        $conversation = Conversation::where('type', 'broadcast')->firstOrFail();
        $this->assertTrue($conversation->is_pinned);
        $this->assertSame('urgent', $conversation->priority);
        $this->assertEqualsCanonicalizing([$sender->id, $selected->id], $conversation->participants()->pluck('users.id')->all());
        $this->actingAs($selected)->get(route('admin.chat', ['conversation' => $conversation->id]))->assertOk()->assertSee('Urgent production update');
        $this->actingAs($excluded)->get(route('admin.chat', ['conversation' => $conversation->id]))->assertOk()->assertDontSee('Urgent production update');
    }

    public function test_inactive_staff_are_not_available_as_recipients(): void
    {
        $sender = $this->staff();
        $inactive = $this->staff(['is_active' => false]);

        Livewire::actingAs($sender)->test(ChatHub::class)
            ->set('directRecipientId', $inactive->id)
            ->call('startDirect')
            ->assertHasErrors('directRecipientId');

        $this->assertDatabaseCount('chat_conversations', 0);
    }
}
