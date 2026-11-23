<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('nama')->unique();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        foreach ([
            'Obat Demam',
            'Obat Batuk',
            'Obat Pilek',
            'Obat Diare',
            'Obat Alergi',
            'Obat Vitamin',
            'Obat Antibiotik',
            'Obat Pencernaan',
            'Lainnya',
        ] as $nama) {
            DB::table('categories')->insert([
                'nama' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
