<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Schema::withoutForeignKeyConstraints(function () {
            DB::table('permissions')->truncate();
        });

        $file = database_path('import/permissions.csv');
        $handle = fopen($file, "r");

        if ($handle) {
            $header = fgetcsv($handle, 1000, ",");
            while (($data = fgetcsv($handle, 1000, ",", '"')) !== FALSE) {
                DB::table('permissions')->insert([
                    'category' => $data[0],
                    'name' => $data[1],
                    'guard_name' => 'web',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        }
    }
}
