<?php

namespace Tests\Feature;

use App\Models\Friendship;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function friends_can_send_messages_to_each_other(): void
    {
        $friendship = Friendship::factory()->accepted()->create();

        $messageContent = 'Hello!';

        $response = $this->actingAs($friendship->userSmall)->postJson(
            route('message.store', $friendship),
            ['content' => $messageContent]
        );

        $response->assertStatus(201);
        $response->assertJsonPath('content', $messageContent);
        $response->assertJsonPath('sender.id', $friendship->userSmall->id);

        $this->assertDatabaseHas('messages', [
            'friendship_id' => $friendship->id,
            'sender_id' => $friendship->userSmall->id,
            'content' => $messageContent,
        ]);
    }

    #[Test]
    public function users_with_pending_friendship_cannot_send_messages(): void
    {
        $friendship = Friendship::factory()->create();

        $response = $this->actingAs($friendship->userSmall)->postJson(
            route('message.store', $friendship),
            ['content' => 'Hello!']
        );

        $response->assertStatus(403);
        $this->assertDatabaseCount('messages', 0);
    }

    #[Test]
    public function blocked_users_cannot_send_messages(): void
    {
        $friendship = Friendship::factory()->blocked()->create();

        $response = $this->actingAs($friendship->userSmall)->postJson(
            route('message.store', $friendship),
            ['content' => 'Hello!']
        );

        $response->assertStatus(403);
        $this->assertDatabaseCount('messages', 0);
    }

    #[Test]
    public function non_friends_cannot_send_friendship_messages(): void
    {
        $friendship = Friendship::factory()->accepted()->create();
        $outsider = User::factory()->create();

        $response = $this->actingAs($outsider)->postJson(
            route('message.store', $friendship),
            ['content' => 'Hello!']
        );

        $response->assertStatus(403);
        $this->assertDatabaseCount('messages', 0);
    }

    #[Test]
    public function friends_can_view_messages(): void
    {
        $friendship = Friendship::factory()->accepted()->create();

        $message1 = Message::factory()->create([
            'friendship_id' => $friendship->id,
            'sender_id' => $friendship->userSmall->id,
            'content' => 'Hello!',
        ]);

        $message2 = Message::factory()->create([
            'friendship_id' => $friendship->id,
            'sender_id' => $friendship->userBig->id,
            'content' => 'Hi there!',
        ]);

        $response = $this->actingAs($friendship->userSmall)->getJson(
            route('message.index', $friendship)
        );

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonPath('data.0.content', $message1->content);
        $response->assertJsonPath('data.1.content', $message2->content);
    }

    #[Test]
    public function users_with_pending_friendship_cannot_view_messages(): void
    {
        $friendship = Friendship::factory()->create();

        $response = $this->actingAs($friendship->userSmall)->getJson(
            route('message.index', $friendship)
        );

        $response->assertStatus(403);
    }

    #[Test]
    public function blocked_users_cannot_view_messages(): void
    {
        $friendship = Friendship::factory()->blocked()->create();

        $response = $this->actingAs($friendship->userBig)->getJson(
            route('message.index', $friendship)
        );

        $response->assertStatus(403);
    }

    #[Test]
    public function outsiders_cannot_view_friendship_messages(): void
    {
        $friendship = Friendship::factory()->accepted()->create();
        $outsider = User::factory()->create();

        $response = $this->actingAs($outsider)->getJson(
            route('message.index', $friendship)
        );

        $response->assertStatus(403);
    }

    #[Test]
    public function message_content_is_required(): void
    {
        $friendship = Friendship::factory()->accepted()->create();

        // Act
        $response = $this->actingAs($friendship->userSmall)->postJson(
            route('message.store', $friendship),
            []
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content']);
        $this->assertDatabaseCount('messages', 0);
    }
}
