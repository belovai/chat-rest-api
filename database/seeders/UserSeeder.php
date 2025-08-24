<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create(['name' => 'Foo', 'email' => 'foo@example.com']);

        User::factory(20)->unverified()->create();
        User::factory(100)->create();
    }
}
