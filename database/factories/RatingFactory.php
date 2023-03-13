<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Rating::class, function (Faker $faker) {
	return [
		'customer_id' => function(){
			return factory(App\Models\Customer::class)->create()->id;
		},
		'rating' => rand(1, 5)
	];
});

$factory->state(App\Models\Rating::class, 'one', [
    'rating' => 1
]);

$factory->state(App\Models\Rating::class, 'two', [
    'rating' => 2
]);

$factory->state(App\Models\Rating::class, 'three', [
    'rating' => 3
]);

$factory->state(App\Models\Rating::class, 'four', [
    'rating' => 4
]);

$factory->state(App\Models\Rating::class, 'five', [
    'rating' => 5
]);