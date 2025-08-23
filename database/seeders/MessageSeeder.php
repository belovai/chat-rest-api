<?php

namespace Database\Seeders;

use App\Enums\FriendshipStatusEnum;
use App\Models\Friendship;
use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<Friendship> $friendships */
        $friendships = Friendship::where('status', FriendshipStatusEnum::Accepted)->inRandomOrder()->take(50)->get();

        if ($friendships->isEmpty()) {
            $this->command->warn('No accepted friendships found. Please run FriendshipSeeder first.');

            return;
        }

        foreach ($friendships as $friendship) {
            for ($i = 0; $i < rand(5, 30); $i++) {
                Message::create([
                    'friendship_id' => $friendship->id,
                    'sender_id' => fake()->randomElement([$friendship->user_id_small, $friendship->user_id_big]),
                    'content' => fake()->sentence,
                    'created_at' => now()->subDays(rand(1, 5)),
                ]);
            }
        }
    }
}
