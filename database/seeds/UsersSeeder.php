<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class UsersSeeder extends \BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $baseUsers = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('qwe123'),
                'role' => 'super-admin',
                //'permission' => 'admin actions'
            ],
            //[
            //	'name' => 'Admin',
            //   'email' => 'admin@example.com',
            //   'password' => bcrypt('qwe123'),
            //   'role' => 'admin',
            //   'permission' => 'admin actions'
            //],
            [
                'name' => 'Provider',
                'email' => 'provider@example.com',
                'password' => bcrypt('qwe123'),
                'role' => 'provider',
                //'permission' => 'provider actions'
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@example.com',
                'password' => bcrypt('qwe123'),
                'role' => 'customer',
                //'permission' => 'customer actions'
            ],
            [
                'name' => 'Joe',
                'email' => 'joe.watson@heritageup.com',
                'password' => bcrypt('TestUserForJoe18'),
                'role' => 'admin',
                // 'permission' => 'customer actions'
            ]
        ];

        foreach ($baseUsers as $u) {
            $user = new App\Models\User();
            $user->name = $u['name'];
            $user->email = $u['email'];
            $user->password = $u['password'];
            $user->save();

            $user->assignRole($u['role']);

            switch ($u['name']){
                //case "Provider":
                //    $provider = factory(App\Models\Provider::class)->create();
                //    $platinumMembershipType = factory(App\Models\MembershipType::class)->states('platinum')->create();
                //    $provider->ratings()->save(factory(App\Models\Rating::class)->create());
                //    $platinumMembershipType->providers()->save($provider);
                //    $company = factory(App\Models\Company::class)->create();
                //    $company->providers()->save($provider);
                //    $provider->categories()->attach(Category::inRandomOrder()->first());
                //    $user->provider()->save($provider);
                //    $provider->save();
                //    break;
                case "Customer":
                    $customer = factory(App\Models\Customer::class)->create();
                    $user->customer()->save($customer);
                    $customer->save();
                    break;
                case "Joe":
                    $customer = factory(App\Models\Customer::class)->create();
                    $user->customer()->save($customer);
                    $customer->save();
                    break;
                default:
                    break;
            }
        }

    }
}
