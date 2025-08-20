<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/logout');

        $response->assertStatus(204);
        $this->assertCount(0, $user->fresh()->tokens);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_logout_endpoint(): void
    {
        $response = $this->getJson('/api/logout');

        $response->assertStatus(401);
    }
}
