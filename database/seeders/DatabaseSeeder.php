<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create some comments
        $comment1 = Comment::create([
            'user_id' => $user->id,
            'body' => 'This is the first comment!',
        ]);

        // Create replies
        Comment::create([
            'user_id' => $user->id,
            'parent_id' => $comment1->id,
            'body' => 'This is a reply to the first comment.',
        ]);
    }
}