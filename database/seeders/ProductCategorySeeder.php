<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productCategories = [
            'Kit Modul',
            'Barang Jadi',
            'Pcb',
            'Papan Nama Akrilik',
            'Box Jam',
            'Pigura',
            'Jam',
            'Segment & Dot Matrix',

        ];

        foreach ($productCategories as $productCategory) {
            ProductCategory::create([
                'nama_kategori' => $productCategory,
            ]);
        }
    }
}
