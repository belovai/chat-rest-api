<?php

namespace Database\Factories;

use App\Models\Friendship;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $friendship = Friendship::factory()->create();

        return [
            'friendship_id' => $friendship->id,
            'sender_id' => $friendship->user_id_small,
            'content' => $this->faker->sentence,
        ];
    }
}
