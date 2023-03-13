<?php

use App\Models\Post;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Models\Post::class, function (Faker $faker) {
	return [
		'title' => $faker->sentence,
        'slug' => $faker->slug,
		'body' => $faker->paragraph,
        'category_id' => function(){
            return DB::table('categories')->inRandomOrder()->first()->id;
        },
        'publish_date' => $faker->dateTimeBetween('2019-02-01', '2019-04-15')->format('Y-m-d H:i:s'),
		'status' => 'published',
        'author_id' => function(){
            return DB::table('post_authors')->inRandomOrder()->first()->id;
        }
	];
});

$factory->state(App\Models\Post::class, 'featured', [
   'featured' => true,
]);

$factory->state(App\Models\Post::class, 'published', [
    'status' => 'published',
]);

$factory->state(App\Models\Post::class, 'unpublished', [
    'status' => 'unpublished',
    'featured' => false,
]);

$factory->state(App\Models\Post::class, 'draft', [
    'status' => 'draft',
    'featured' => false,
]);

$factory->state(App\Models\Post::class, 'featured-published', [
    'featured' => true,
    'status' => 'published',
    'precedence' => 0,
]);

$factory->state(App\Models\Post::class, 'past published', [
    'publish_date' => Carbon::now()->subWeek(),
]);

$factory->state(App\Models\Post::class, 'future published', [
    'publish_date' => Carbon::now()->addWeek(),
]);