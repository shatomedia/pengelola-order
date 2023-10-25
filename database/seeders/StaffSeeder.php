<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = Role::create([
            'name' => 'staff'
        ]);

        $user = User::create([
            'name' => 'Staff',
            'username' => Str::uuid(),
            'email' => 'staff@staff.com',
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole($staff->id);

        $staff->givePermissionTo('dashboard');
        $staff->givePermissionTo('account');
    }
}
