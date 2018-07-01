<?php

use Illuminate\Database\Seeder;

class MstPostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Model\Post\Post::class, 10)->create();
    }
}
