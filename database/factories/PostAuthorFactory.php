<?php

use Faker\Generator as Faker;

$factory->define(App\Models\PostAuthor::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'slug' => $faker->slug,
        'bio' => $faker->paragraph,
        'active' => 1,
    ];
});