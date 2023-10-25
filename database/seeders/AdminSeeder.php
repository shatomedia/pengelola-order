<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create([
            'name' => 'admin',
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'username' => Str::uuid(),
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678')
        ]);

        $admin->assignRole($role->id);

        $permissions = Permission::pluck('id','id')
            ->all();

        $role->syncPermissions($permissions);
    }
}
