<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserListingTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_can_list_users(): void
    {
        $users = User::factory()->count(15)->create();
        Sanctum::actingAs($users->first());

        $response = $this->getJson('/api/users');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data',
            'links',
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);

        $response->assertJsonCount(15, 'data');
        $this->assertEquals(15, $response->json('meta.total'));

        $response = $this->getJson('/api/users?page=2');
        $response->assertStatus(200);

        $response->assertJsonCount(0, 'data');
        $this->assertEquals(2, $response->json('meta.current_page'));
    }

    #[Test]
    public function authenticated_user_can_filter_user_list_by_name(): void
    {
        $users = User::factory()->count(5)->create();
        Sanctum::actingAs($users->first());

        User::factory()->create([
            'name' => 'Specific Test User',
        ]);

        $response = $this->getJson('/api/users?name=Specific Test User');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');

        $response = $this->getJson('/api/users?name=Specific');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    #[Test]
    public function authenticated_user_can_filter_user_list_by_email(): void
    {
        $users = User::factory()->count(5)->create();
        Sanctum::actingAs($users->first());

        User::factory()->create([
            'email' => 'specific@example.com',
        ]);

        $response = $this->getJson('/api/users?email=specific@example.com');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');

        $response = $this->getJson('/api/users?email=specific');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    #[Test]
    public function authenticated_user_can_filter_user_list_by_multiple_criteria(): void
    {
        $users = User::factory()->count(5)->create();
        Sanctum::actingAs($users->first());

        User::factory()->create([
            'name' => 'John Smith',
            'email' => 'john.smith@example.com',
        ]);

        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
        ]);

        $response = $this->getJson('/api/users?name=John&email=smith');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    #[Test]
    public function visitor_cannot_list_users(): void
    {
        User::factory()->count(5)->create();

        $response = $this->getJson('/api/users');
        $response->assertStatus(401);
    }
}
