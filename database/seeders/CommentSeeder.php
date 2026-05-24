<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Resource;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::with('profile')->get();

        foreach ($users as $user) {
            $templates = Template::where('user_id', $user->id)->take(2)->get();
            foreach ($templates as $template) {
                Comment::factory()
                    ->forTemplate($template)
                    ->for($user)
                    ->count(2)
                    ->create();
            }

            $resources = Resource::where('user_id', $user->id)->take(2)->get();
            foreach ($resources as $resource) {
                Comment::factory()
                    ->forResource($resource)
                    ->for($user)
                    ->count(2)
                    ->create();
            }
        }
    }
}