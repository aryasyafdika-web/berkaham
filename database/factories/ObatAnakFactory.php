<?php

namespace Database\Factories;

use App\Models\ObatAnak;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ObatAnak>
 */
class ObatAnakFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_obat' => fake()->randomElement(['Paracetamol', 'Amoxicillin', 'CTM', 'OBH Anak', 'Zinc']),
            'kategori' => fake()->randomElement(['Demam', 'Antiinfeksi', 'Batuk/Pilek', 'Suplement']),
            'bentuk' => fake()->randomElement(['Sirup', 'Tablet', 'Tetes', 'Salep']),
            'dosis' => fake()->randomElement(['120 mg/5 ml', '125 mg/5 ml', '5 mg', '20 mg/ml']),
            'stok' => fake()->numberBetween(0, 100),
            'satuan' => fake()->randomElement(['botol', 'strip', 'tube', 'sachet']),
            'tanggal_kedaluwarsa' => fake()->optional()->dateTimeBetween('+1 month', '+2 years'),
            'keterangan' => fake()->optional()->sentence(),
        ];
    }
}
