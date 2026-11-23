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
        Schema::table('obat_anaks', function (Blueprint $table): void {
            $table->foreignId('category_id')->nullable()->after('id')->constrained('categories')->restrictOnDelete();
            $table->unsignedInteger('stok_minimum')->default(5)->after('stok');
        });

        $categoryNames = DB::table('obat_anaks')->distinct()->pluck('kategori');

        foreach ($categoryNames as $categoryName) {
            $nama = match (strtolower($categoryName)) {
                'demam' => 'Obat Demam',
                'antiinfeksi' => 'Obat Antibiotik',
                'suplement', 'suplemen' => 'Obat Vitamin',
                'pencernaan' => 'Obat Pencernaan',
                'alergi' => 'Obat Alergi',
                default => $categoryName,
            };

            DB::table('categories')->insertOrIgnore([
                'nama' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $categoryId = DB::table('categories')->where('nama', $nama)->value('id');

            DB::table('obat_anaks')
                ->where('kategori', $categoryName)
                ->update(['category_id' => $categoryId, 'kategori' => $nama]);
        }

        Schema::create('stock_histories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('obat_anak_id')->constrained('obat_anaks')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('jenis', 20);
            $table->unsignedInteger('jumlah');
            $table->unsignedInteger('stok_sebelum');
            $table->unsignedInteger('stok_sesudah');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->index(['obat_anak_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_histories');

        Schema::table('obat_anaks', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn('stok_minimum');
        });
    }
};
