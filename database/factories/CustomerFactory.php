<?php

use Faker\Generator as Faker;
use Grimzy\LaravelMysqlSpatial\Types\Point;

$factory->define(App\Models\Customer::class, function (Faker $faker) {
    return [
        'user_id' => function() {
            $user = factory(App\Models\User::class)->create();
            $user->assignRole('customer');
            return $user->id;
        },
        'zip' => \App\Models\Zip::inRandomOrder()->first()->zipcode,
        'age' => $faker->numberBetween(40, 75),
        'geo_point' => new Point($faker->latitude, $faker->longitude),
    ];
});
