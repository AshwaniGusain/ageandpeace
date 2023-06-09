<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleAndPermissionsSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(MembershipTypesSeeder::class);
        $this->call(ProviderTypeSeeder::class);
        $this->call(TaskSeeder::class);
        $this->call(ZipsSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(PostAuthorsSeeder::class);
        $this->call(PostsSeeder::class);
        $this->call(ProvidersSeeder::class);
        $this->call(CustomerSeeder::class);
    }
}
