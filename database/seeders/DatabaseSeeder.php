<?php

namespace Database\Seeders;

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
        ObatAnak::factory()->create([
            'nama_obat' => 'Paracetamol Anak',
            'kategori' => 'Demam',
            'bentuk' => 'Sirup',
            'dosis' => '120 mg/5 ml',
            'stok' => 24,
            'satuan' => 'botol',
            'tanggal_kedaluwarsa' => '2027-08-31',
        ]);
        ObatAnak::factory()->create([
            'nama_obat' => 'Amoxicillin',
            'kategori' => 'Antiinfeksi',
            'bentuk' => 'Suspensi',
            'dosis' => '125 mg/5 ml',
            'stok' => 8,
            'satuan' => 'botol',
            'tanggal_kedaluwarsa' => '2027-12-20',
        ]);
        ObatAnak::factory()->create([
            'nama_obat' => 'OBH Combi Anak',
            'kategori' => 'Batuk/Pilek',
            'bentuk' => 'Sirup',
            'dosis' => '100 ml',
            'stok' => 0,
            'satuan' => 'botol',
            'tanggal_kedaluwarsa' => '2027-06-15',
        ]);
        ObatAnak::factory()->create([
            'nama_obat' => 'Zinc Syrup',
            'kategori' => 'Suplement',
            'bentuk' => 'Sirup',
            'dosis' => '20 mg/5 ml',
            'stok' => 15,
            'satuan' => 'botol',
            'tanggal_kedaluwarsa' => '2028-03-10',
        ]);
    }
}
