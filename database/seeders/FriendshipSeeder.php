<?php

namespace Database\Seeders;

use App\Enums\FriendshipStatusEnum;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\UniqueConstraintViolationException;

class FriendshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userCount = User::count();

        if ($userCount < 5) {
            $this->command->warn('Not enough users found. Please run UserSeeder first.');
            return;
        }

        $foo = User::where('email', 'foo@example.com')->first();
        $users = User::inRandomOrder()->take(10)->get();
        $users->prepend($foo);

        $users->each(function ($user) {
            $friends = User::inRandomOrder()->take(rand(10, 30))->get();
            $friends->each(function ($friend) use ($user) {
                if ($user->id === $friend->id) {
                    return;
                }
                try {
                    Friendship::create([
                        'user_id_small' => min($user->id, $friend->id),
                        'user_id_big' => max($user->id, $friend->id),
                        'status' => fake()->randomElement([FriendshipStatusEnum::Pending, FriendshipStatusEnum::Accepted]),
                        'requested_by' => fake()->randomElement([$user->id, $friend->id]),
                    ]);
                } catch (UniqueConstraintViolationException $e) {
                    // Ignore duplicate entries
                }
            });
        });
    }
}
