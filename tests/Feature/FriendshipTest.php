<?php

namespace Tests\Feature;

use App\Enums\FriendshipStatusEnum;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FriendshipTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_send_friendship_request(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();
        Sanctum::actingAs($sender);

        $response = $this->postJson(route('friendship.store', ['user' => $recipient->id]));

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'user_id_small',
            'user_id_big',
            'status',
            'requested_by',
        ]);
        $this->assertDatabaseHas('friendships', [
            'user_id_small' => min($sender->id, $recipient->id),
            'user_id_big' => max($sender->id, $recipient->id),
            'status' => FriendshipStatusEnum::Pending,
            'requested_by' => $sender->id,
        ]);
    }

    #[Test]
    public function cannot_send_friendship_request_to_self(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('friendship.store', ['user' => $user->id]));

        $response->assertStatus(403);

        $this->assertDatabaseMissing('friendships', [
            'user_id_small' => $user->id,
            'user_id_big' => $user->id,
        ]);
    }

    #[Test]
    public function cannot_send_duplicate_friendship_request(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();
        Sanctum::actingAs($sender);

        $this->postJson(route('friendship.store', ['user' => $recipient->id]));

        $response = $this->postJson(route('friendship.store', ['user' => $recipient->id]));
        $response->assertStatus(409); // Conflict
        $this->assertDatabaseCount('friendships', 1);
    }

    #[Test]
    public function unauthenticated_user_cannot_send_friendship_request(): void
    {
        $recipient = User::factory()->create();

        $response = $this->postJson(route('friendship.store', ['user' => $recipient->id]));

        $response->assertStatus(401);
    }

    #[Test]
    public function user_can_accept_friend_request(): void
    {
        $friendship = Friendship::factory()->create();
        $recipient = $friendship->requested_by == $friendship->user_id_small ? $friendship->userBig : $friendship->userSmall;

        Sanctum::actingAs($recipient);
        $response = $this->patchJson(
            route(
                'friendship.accept',
                ['friendship' => $friendship->id]
            )
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('friendships', [
            'id' => $friendship->id,
            'status' => FriendshipStatusEnum::Accepted,
        ]);
    }

    #[Test]
    public function user_can_delete_friendship_request(): void
    {
        $friendship = Friendship::factory()->create();

        Sanctum::actingAs($friendship->requestedBy);
        $response = $this->deleteJson(route(
            'friendship.destroy',
            ['friendship' => $friendship->id]
        ));

        $response->assertStatus(204);
    }

    #[Test]
    public function user_can_reject_friendship_request(): void
    {
        $friendship = Friendship::factory()->create();
        $recipient = $friendship->requested_by == $friendship->user_id_small ? $friendship->userBig : $friendship->userSmall;

        Sanctum::actingAs($recipient);
        $response = $this->deleteJson(route(
            'friendship.destroy',
            ['friendship' => $friendship->id]
        ));

        $response->assertStatus(204);
    }

    #[Test]
    public function user_can_delete_friendship(): void
    {
        $friendship = Friendship::factory()->accepted()->create();

        Sanctum::actingAs($friendship->requestedBy);
        $response = $this->deleteJson(route(
            'friendship.destroy',
            ['friendship' => $friendship->id]
        ));

        $response->assertStatus(204);
    }
}
