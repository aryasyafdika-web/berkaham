<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('obat_anaks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_obat');
            $table->string('kategori');
            $table->string('bentuk');
            $table->string('dosis')->nullable();
            $table->unsignedInteger('stok')->default(0);
            $table->string('satuan')->default('botol');
            $table->date('tanggal_kedaluwarsa')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['kategori', 'nama_obat']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat_anaks');
    }
};
