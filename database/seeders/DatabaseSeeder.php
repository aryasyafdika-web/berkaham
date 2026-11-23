<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ObatAnak;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ([
            ['nama_obat' => 'Paracetamol Anak', 'kategori' => 'Obat Demam', 'bentuk' => 'Sirup', 'dosis' => '120 mg/5 ml', 'stok' => 24, 'satuan' => 'Botol', 'tanggal_kedaluwarsa' => '2027-08-31'],
            ['nama_obat' => 'Amoxicillin', 'kategori' => 'Obat Antibiotik', 'bentuk' => 'Sirup', 'dosis' => '125 mg/5 ml', 'stok' => 8, 'satuan' => 'Botol', 'tanggal_kedaluwarsa' => '2027-12-20'],
            ['nama_obat' => 'OBH Combi Anak', 'kategori' => 'Obat Batuk', 'bentuk' => 'Sirup', 'dosis' => '100 ml', 'stok' => 0, 'satuan' => 'Botol', 'tanggal_kedaluwarsa' => '2027-06-15'],
            ['nama_obat' => 'Zinc Syrup', 'kategori' => 'Obat Diare', 'bentuk' => 'Sirup', 'dosis' => '20 mg/5 ml', 'stok' => 15, 'satuan' => 'Botol', 'tanggal_kedaluwarsa' => '2028-03-10'],
        ] as $medicine) {
            $category = Category::firstOrCreate(['nama' => $medicine['kategori']]);

            ObatAnak::firstOrCreate(
                ['nama_obat' => $medicine['nama_obat']],
                [...$medicine, 'category_id' => $category->id, 'stok_minimum' => 5],
            );
        }

    }
}
