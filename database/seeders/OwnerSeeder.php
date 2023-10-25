<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = Role::create([
            'name' => 'owner'
        ]);

        $user = User::create([
            'name' => 'Owner',
            'username' => Str::uuid(),
            'email' => 'owner@owner.com',
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole($owner->id);

        $owner->givePermissionTo('dashboard');
        $owner->givePermissionTo('account');
        
    }
}
