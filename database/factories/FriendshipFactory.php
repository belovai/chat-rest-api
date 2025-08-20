<?php

namespace Database\Factories;

use App\Enums\FriendshipStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Friendship>
 */
class FriendshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function definition(): array
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        [$small, $big] = $this->normalize($userA->id, $userB->id);

        return [
            'user_id_small' => $small,
            'user_id_big' => $big,
            'status' => FriendshipStatusEnum::Pending,
            'requested_by' => $this->faker->randomElement([$userA->id, $userB->id]),
            'accepted_at' => null,
            'blocked_by' => null,
        ];
    }

    /**
     * @param int $x
     * @param int $y
     * @return array{int, int}
     */
    private function normalize(int $x, int $y): array
    {
        return $x < $y ? [$x, $y] : [$y, $x];
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => FriendshipStatusEnum::Accepted,
            'accepted_at' => now(),
        ]);
    }

    public function blocked(int $blockedBy = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => FriendshipStatusEnum::Blocked,
            'blocked_by' => $blockedBy ?? $attributes['requested_by'],
        ]);
    }

}
