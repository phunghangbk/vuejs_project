<?php

use Illuminate\Database\Seeder;

class MstUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Model\User::class, 50)->create()->each(function ($u) {
            $u->posts()->save(factory(App\Model\Post\Post::class)->make());
        });
    }
}
