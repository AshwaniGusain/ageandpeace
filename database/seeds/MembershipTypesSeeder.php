<?php

use Illuminate\Database\Seeder;
use App\Models\MembershipType;

class MembershipTypesSeeder extends \BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MembershipType::class)->states('platinum')->create();
        factory(MembershipType::class)->states('gold')->create();
        factory(MembershipType::class)->states('silver')->create();
        factory(MembershipType::class)->create();
    }


}
