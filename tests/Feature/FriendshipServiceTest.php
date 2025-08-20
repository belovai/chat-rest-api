<?php

namespace Tests\Feature;

use App\Enums\FriendshipStatusEnum;
use App\Models\User;
use App\Services\FriendshipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FriendshipServiceTest extends TestCase
{
    use RefreshDatabase;

    private FriendshipService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(FriendshipService::class);
    }

    #[Test]
    public function user_can_send_friend_request(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $friendship = $this->service->request($userA, $userB);

        $this->assertDatabaseHas('friendships', [
            'user_id_small' => min($userA->id, $userB->id),
            'user_id_big' => max($userA->id, $userB->id),
            'status' => FriendshipStatusEnum::Pending,
            'requested_by' => $userA->id,
        ]);

        $this->assertTrue($friendship->exists);
        $this->assertEquals(FriendshipStatusEnum::Pending, $friendship->status);
    }
}
