<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use \App\Models\User;
use \App\Models\Post;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $NUM_USERS = 5;

        for ($i = 0; $i < $NUM_USERS; $i++) {
            $public_posts = Post::factory()->count(2)->for($user = User::factory(), 'sender_model')->state(['receiver' => null, 'isPrivate' => false])->create();
        }

        $private_posts = Post::factory()->count(10)->state((new Sequence(
            function ($sequence) {
                return ['isPrivate' => true, 'sender' => User::all()->random(), 'receiver' => User::all()->random()];
            },
        )))->create();
    }
}
