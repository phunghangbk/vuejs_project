<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Post\Post::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'title' => $faker->title,
        'content' => $faker->text,
        'introduction' => $faker->text
    ];
});
