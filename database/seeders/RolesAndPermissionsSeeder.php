<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Créer un rôle admin
        $adminRole = Role::create(['name' => 'admin']);

        // Créer des permissions pour la table des produits
        $AdminPermissions = [
            'view_all_products',
            'create_product',
            'edit_product',
            'delete_product',
            'delete_fournisseurs',
            'update_fournisseurs',
            'view_fournisseurs',
            'create_fournisseurs',
            'view_all_fournisseurs',
            'delete_user',
            'edit_user',
            'view_user',
            'create_user',
            'view_all_users',
            'delete_clients',
            'view_clients',
            'update_clients',
            'view_all_clients',
            'create_clients',
        ];
        foreach ($AdminPermissions as $permission) {
            Permission::create(['name' => $permission]);
            $adminRole->givePermissionTo($permission);
        }

        // Créer un utilisateur avec le rôle admin et les permissions associées
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'admin1234',
        ]);
        $adminUser->assignRole($adminRole);

        // Créer un utilisateur avec le rôle utilisateur et la permission view_all_products
        // $userRole = Role::create(['name' => 'utilisateur']);
        // $viewAllProductsPermission = Permission::create(['name' => 'view_all_products']);
        // $userRole->givePermissionTo('view_all_products');

        // $regularUser = User::create([
        //     'name' => 'Regular User',
        //     'email' => 'user@example.com',
        //   'password' => 'password',
        // ]);
        // $regularUser->assignRole($userRole);
    }
}
