<?php

use Faker\Generator as Faker;
use Grimzy\LaravelMysqlSpatial\Types\Point;

$factory->define(App\Models\Provider::class, function (Faker $faker) {
    return [
        'user_id'         => function () {
            $user = factory(App\Models\User::class)->create();
            $user->assignRole('provider');

            return $user->id;
        },
        'provider_type_id' => \App\Models\ProviderType::inRandomOrder()->first()->id,
        'street'          => $faker->streetAddress,
        'city'            => $faker->city,
        'state'           => $faker->stateAbbr,
        'zip'             => \App\Models\Zip::inRandomOrder()->first()->zipcode,
        'phone'           => $faker->phoneNumber,
        'website'         => $faker->url,
        'description'     => $faker->text,
        'expiration_date' => $faker->date(),
        'company_id'      => function () {
            return factory(App\Models\Company::class)->create()->id;
        },
        'slug'            => $faker->slug(4),
        'geo_point' => new Point($faker->latitude, $faker->longitude),
    ];
});

$factory->state(App\Models\Provider::class, 'noCompany', [
    'company_id' => null,
]);




