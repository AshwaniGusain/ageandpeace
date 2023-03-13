<?php

use Illuminate\Database\Seeder;

class PostAuthorsSeeder extends \BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\PostAuthor::class, 1)->create([
            'name' => 'Tom Jones',
        ])->each(function ($a){
            $a->save();
        });
    }
}
