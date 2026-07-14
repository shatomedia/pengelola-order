<?php

namespace Database\Seeders;

use App\Models\TransactionCategory;
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
            'pengeluaran-berulang',
            'pengeluaran-berulang-create',
            'pengeluaran-berulang-edit',
            'pengeluaran-berulang-delete',
            'kategori-keuangan',
            'kategori-keuangan-create',
            'kategori-keuangan-edit',
            'kategori-keuangan-delete',
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

        $defaultCategories = [
            ['nama_kategori' => 'Penjualan Lain-lain', 'jenis' => 'pemasukan'],
            ['nama_kategori' => 'Proyek IoT', 'jenis' => 'pemasukan'],
            ['nama_kategori' => 'Pemasukan Lainnya', 'jenis' => 'pemasukan'],
            ['nama_kategori' => 'Listrik', 'jenis' => 'pengeluaran'],
            ['nama_kategori' => 'Internet', 'jenis' => 'pengeluaran'],
            ['nama_kategori' => 'Domain & Hosting', 'jenis' => 'pengeluaran'],
            ['nama_kategori' => 'Gaji', 'jenis' => 'pengeluaran'],
            ['nama_kategori' => 'Operasional', 'jenis' => 'pengeluaran'],
            ['nama_kategori' => 'Produksi', 'jenis' => 'pengeluaran'],
            ['nama_kategori' => 'Pengeluaran Lainnya', 'jenis' => 'pengeluaran'],
        ];

        foreach ($defaultCategories as $category) {
            TransactionCategory::firstOrCreate(
                ['nama_kategori' => $category['nama_kategori'], 'jenis' => $category['jenis']]
            );
        }
    }
}
