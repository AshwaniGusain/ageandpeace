<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class ProviderLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attorney = new \App\Models\ProviderLabel(['label' => 'Attorney']);
        $attorney->save();
        $attorney->categories()->attach(Category::find(65));
        $attorney->categories()->attach(Category::find(66));
        $attorney->categories()->attach(Category::find(67));

        $financial_advisor = new \App\Models\ProviderLabel(['label' => 'Financial Advisor']);
        $financial_advisor->save();
        $financial_advisor->categories()->attach(Category::find(41));
        $financial_advisor->categories()->attach(Category::find(117));

        $insurance = new \App\Models\ProviderLabel(['label' => 'Insurance']);
        $insurance->save();
        $insurance->categories()->attach(Category::find(45));
        $insurance->categories()->attach(Category::find(46));
        $insurance->categories()->attach(Category::find(47));
    }
}
