<?php

use Illuminate\Database\Seeder;

class CustomerSeeder extends \BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Customer::class, 3)->create();
    }
}
