<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;


class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = [
            'edit-profile',
            'view-profile',

            'create-role',
            'edit-role',
            'view-role',
            'view-all-roles',
            'delete-role',

            'create-user',
            'edit-user',
            'view-user',
            'view-all-users',
            'delete-user',

            'create-product',
            'edit-product',
            'delete-product',

            'create-category',
            'edit-category',
            'delete-category',

            'view-all-delivery-costs',
            'view-delivery-cost',
            'creat-delivery-cost',
            'edit-delivery-cost',
            'delete-delivery-cost',

            'view-all-orders',
            'view-order',
            'create-order',
            'edit-order',        
        ];

        foreach ($permissions as $permission) 
        {
            Permission::create(['name' => $permission]);
        }

       $client = Role::create(['guard_name' => 'api','name' => 'client']);
       $client->syncPermissions([
            'edit-profile',
            'view-profile',

            'create-order',
            'edit-order',
            'view-all-orders',
            'view-order',
       ]);

       $supervisor =  Role::create(['guard_name' => 'api','name' => 'supervisor']);
       $supervisor->syncPermissions([
            'edit-user',
            'view-user',
            'view-all-users',
            
            'create-product',
            'edit-product',
            'delete-product',

            'create-category',
            'edit-category',
            'delete-category',

            'view-all-orders',
            'view-order',
            'edit-order',

            'view-all-delivery-costs',
            'view-delivery-cost',
            'creat-delivery-cost',
            'edit-delivery-cost',
            'delete-delivery-cost',


         ]);
         
       $driver =  Role::create(['guard_name' => 'api','name' => 'driver']);
       $driver->syncPermissions([
        'edit-profile',
        'view-profile',

        'edit-order',
        'view-all-orders',
        'view-order',
   ]);


    }
}
