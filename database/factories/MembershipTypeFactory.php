<?php

use Faker\Generator as Faker;

$factory->define(App\Models\MembershipType::class, function (Faker $faker) {
	return [
		'tier' => 'Bronze',
		'description' => $faker->text,
		'price' => $faker->randomFloat(2,1,10000),
		'term_length' => rand(1, 12) . ' months'
	];
});

$factory->state(App\Models\MembershipType::class, 'silver', [
	'tier' => 'Silver',
]);

$factory->state(App\Models\MembershipType::class, 'gold', [
	'tier' => 'Gold',
]);

$factory->state(App\Models\MembershipType::class, 'platinum', [
    'tier' => 'Platinum',
]);