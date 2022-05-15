<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
class PermissionSeeder extends Seeder
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

         //Create Permission
         $permissions = [
             'user_management_access',
             'permission_create',
             'permission_edit',
             'permission_show',
             'permission_delete',
             'permission_access',
             'user_create',
             'user_edit',
             'user_show',
             'user_delete',
             'user_access',
             'product_create',
             'product_edit',
             'product_show',
             'product_delete',
             'product_access',
             'market_access',
             'cart_create',
             'cart_update',
             'cart_delete',
             'cart_access',
             'order_access',
             'rider_access',
             'comment_create',
             'comment_edit',
             'comment_show',
             'comment_delete',
             'comment_access',
         ];

        foreach ($permissions as $permission)   {
            Permission::create([
                'name' => $permission
            ]);
        }

        // gets all permissions via Gate::before rule; see AuthServiceProvider
        Role::create(['name' => 'Super Admin']);
        $user = Role::create(['name' => 'User']);
        $seller = Role::create(['name' => 'Seller']);
        $rider = Role::create(['name' => 'Rider']);

        $userPermissions = [
            'market_access',
            'cart_create',
            'cart_update',
            'cart_delete',
            'cart_access',
            'order_access',
            'comment_create',
            'comment_edit',
            'comment_show',
            'comment_delete',
            'comment_access',
        ];

        foreach ($userPermissions as $permission)   {
            $user->givePermissionTo($permission);
        }

        $sellerPermission = [
            'market_access',
            'order_access',
            'product_create',
            'product_edit',
            'product_show',
            'product_delete',
            'product_access',
        ];

        foreach ($sellerPermission as $permission)   {
            $seller->givePermissionTo($permission);
        }

        $riderPermission = [
            'market_access',
            'order_access',
            'rider_access'
        ];

        foreach ($riderPermission as $permission)   {
            $rider->givePermissionTo($permission);
        }
    }
}
