<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FinanceModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'pemasukan',
            'pemasukan-create',
            'pemasukan-edit',
            'pemasukan-delete',
            'pengeluaran',
            'pengeluaran-create',
            'pengeluaran-edit',
            'pengeluaran-delete',
            'laporan-keuangan',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['category' => 'keuangan']
            );
        }

        $finance = Role::firstOrCreate(['name' => 'finance']);
        $finance->givePermissionTo($permissions);

        $owner = Role::where('name', 'owner')->first();
        if ($owner) {
            $owner->givePermissionTo($permissions);
        }
    }
}
