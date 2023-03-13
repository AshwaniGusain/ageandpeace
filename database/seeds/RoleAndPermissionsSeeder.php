<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionsSeeder extends Seeder
{
    /**
     * Run the Role and Permissions seed to generate specific user roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::create(['name' => 'super-admin']);

        Role::create(['name' => 'provider']);
        Role::create(['name' => 'customer']);

        \Admin::seedPermissions();

        $adminRole = Role::create(['name' => 'admin']);
        //$adminRole->givePermissionTo('view dashboard');


        $modules = [
            'dashboard',
            'company',
            'provider',
            'customer',
            'post',
            'task',
            'category',
            'membership_type',
            'media',
            'search',
        ];

        foreach ($modules as $module) {
            \Module::get($module)->givePermissionTo($adminRole, [], true);
        }


        //Permission::create(['name' => 'admin actions']);
        //Permission::create(['name' => 'provider actions']);
        //Permission::create(['name' => 'customer actions']);
        //
        //$admin_role = Role::create(['name' => 'admin']);
        //$admin_role->givePermissionTo('admin actions');
        //
        //$provider_role = Role::create(['name' => 'provider']);
        //$provider_role->givePermissionTo('provider actions');
        //
        //$customer_role = Role::create(['name' => 'customer']);
        //$customer_role->givePermissionTo('customer actions');
    }
}
