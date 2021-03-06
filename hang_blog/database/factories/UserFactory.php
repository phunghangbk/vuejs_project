<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Model\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('Hang27051993'), // secret
        'nickname' => str_random(10),
        'verified' => 1,
        'avatar_image' => 'avatar_image.jpeg',
        'cover_image' => 'cover_image.jpeg'
    ];
});
