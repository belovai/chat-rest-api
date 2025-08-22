<?php

namespace Tests\Feature;

use App\Models\Scopes\VerifiedScope;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function visitor_can_register(): void
    {
        Notification::fake();

        $response = $this->postJson(
            '/api/register',
            [
                'name' => 'Visitor',
                'email' => 'visitor@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]
        );

        $response->assertStatus(201);
        $this->assertDatabaseCount('users', 1);

        $user = User::withoutGlobalScope(VerifiedScope::class)->first();
        $this->assertNotNull($user);
        $this->assertFalse($user->hasVerifiedEmail());

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    #[Test]
    #[DataProvider('invalidEmailProvider')]
    public function must_use_valid_email(?string $email): void
    {
        $response = $this->postJson(
            '/api/register',
            [
                'name' => 'Visitor',
                'email' => $email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]
        );
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    /**
     * @param  array<string, string>  $passwords
     */
    #[Test]
    #[DataProvider('invalidPasswordProvider')]
    public function must_use_valid_password(array $passwords): void
    {
        $response = $this->postJson(
            '/api/register',
            array_merge([
                'name' => 'Visitor',
                'email' => 'visitor@example.com',
            ], $passwords)
        );
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }

    #[Test]
    public function must_use_valid_name(): void
    {
        $response = $this->postJson(
            '/api/register',
            [
                'name' => '',
                'email' => 'visitor@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]
        );
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /**
     * @return array<array<string, string|null>>
     */
    public static function invalidEmailProvider(): array
    {
        return [
            ['email' => 'visitor'],
            ['email' => 'visitor@example'],
            ['email' => 'example.com'],
            ['email' => null],
        ];
    }

    /**
     * @return array<array<array<string, string>>>
     */
    public static function invalidPasswordProvider(): array
    {
        return [
            [[
                'password' => 'password',
                'password_confirmation' => 'not-password',
            ]],
            [[
                'password' => 'pass',
                'password_confirmation' => 'pass',
            ]],
            [[]],
        ];
    }
}
