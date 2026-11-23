<?php

namespace Database\Factories;

use App\Models\Category;
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
            'category_id' => Category::factory(),
            'nama_obat' => fake()->randomElement(['Paracetamol', 'Amoxicillin', 'CTM', 'OBH Anak', 'Zinc']),
            'kategori' => fake()->randomElement(['Demam', 'Antiinfeksi', 'Batuk/Pilek', 'Suplement']),
            'bentuk' => fake()->randomElement(['Sirup', 'Tablet', 'Kapsul', 'Puyer', 'Drops', 'Salep', 'Suppositoria']),
            'dosis' => fake()->randomElement(['120 mg/5 ml', '125 mg/5 ml', '5 mg', '20 mg/ml']),
            'stok' => fake()->numberBetween(0, 100),
            'stok_minimum' => 5,
            'satuan' => fake()->randomElement(['Botol', 'Strip', 'Tablet', 'Kapsul', 'Tube', 'Sachet', 'Pcs']),
            'tanggal_kedaluwarsa' => fake()->optional()->dateTimeBetween('+1 month', '+2 years'),
            'keterangan' => fake()->optional()->sentence(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (ObatAnak $obatAnak): void {
            $obatAnak->update(['kategori' => $obatAnak->category->nama]);
        });
    }
}
