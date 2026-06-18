<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_a_message_to_start_a_conversation(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $response = $this
            ->actingAs($sender)
            ->post("/messages/{$recipient->id}", ['body' => 'Hey, interested in your store!']);

        $response->assertRedirect(route('messages.show', $recipient));

        $conversation = Conversation::between($sender, $recipient);

        $this->assertCount(1, $conversation->messages);
        $this->assertSame('Hey, interested in your store!', $conversation->messages->first()->body);
        $this->assertSame($sender->id, $conversation->messages->first()->sender_id);
    }

    public function test_replies_reuse_the_same_conversation(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $this->actingAs($userA)->post("/messages/{$userB->id}", ['body' => 'Hello']);
        $this->actingAs($userB)->post("/messages/{$userA->id}", ['body' => 'Hi back']);

        $this->assertSame(1, Conversation::count());
        $this->assertSame(2, Conversation::first()->messages()->count());
    }

    public function test_viewing_a_conversation_marks_messages_as_read(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $this->actingAs($sender)->post("/messages/{$recipient->id}", ['body' => 'Hello']);

        $this->assertSame(1, $recipient->unreadMessagesCount());

        $this->actingAs($recipient)->get("/messages/{$sender->id}")->assertOk();

        $this->assertSame(0, $recipient->unreadMessagesCount());
    }

    public function test_user_cannot_message_themselves(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post("/messages/{$user->id}", ['body' => 'Hello me'])
            ->assertForbidden();
    }

    public function test_message_body_is_required(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $this->actingAs($sender)
            ->post("/messages/{$recipient->id}", ['body' => ''])
            ->assertSessionHasErrors('body');
    }

    public function test_guests_cannot_access_messages(): void
    {
        $user = User::factory()->create();

        $this->get('/messages')->assertRedirect('/login');
        $this->get("/messages/{$user->id}")->assertRedirect('/login');
    }
}
