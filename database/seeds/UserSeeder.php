<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\User;
use App\Models\Post;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class, 1)
           ->create([
                'email' => 'admin@admin.com',
                'role'  => 'admin'
           ]);

        $user = factory(User::class, 5)
           ->create()
           ->each(function ($user) {
                $post = factory(Post::class, 2)->create([
                	'author_id' => $user->id,
                	'author'    => $user->name
                ]);
            });
    }
}
