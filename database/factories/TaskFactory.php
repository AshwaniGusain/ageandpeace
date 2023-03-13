<?php

use Faker\Generator as Faker;


$factory->define(App\Models\Task::class, function (Faker $faker) {
    $category = \App\Models\Category::subCategories()->inRandomOrder()->first();

    return [
        'title' => $faker->word,
        'description' => $faker->sentence,
        'category_id' => $category->id
    ];
});
