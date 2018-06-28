<?php

use Faker\Generator as Faker;

$factory->define(App\Post\Post::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'title' => $faker->title,
        'content' => $faker->randomHtml(6, 10),
        'introduction' => $faker->text
    ];
});
