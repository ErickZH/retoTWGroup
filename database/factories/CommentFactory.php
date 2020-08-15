<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'publication_id' => factory(App\Publication::class)->create(),
        'user_id' => factory(\App\User::class)->create(),
        'content' => $faker->sentence,
        'status' => $faker->word,
    ];
});
