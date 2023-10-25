<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'dashboard',
            'account',
            'permission',
            'permission-edit',
            'permission-delete',
            'role-edit',
            'order',
            'order-create',
            'order-edit',
            'order-delete',
            'product',
            'product-create',
            'product-edit',
            'product-delete',
            'product-category',
            'product-category-create',
            'product-category-edit',
            'product-category-delete',
            'user-management',
            'user-management-create',
            'user-management-edit',
            'user-management-delete',
        ];

        foreach ($permissions as $permission){
            Permission::create([
                'name' => $permission
            ]);
        }
    }
}
