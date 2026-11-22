<?php

namespace App\Models;

use Database\Factories\ObatAnakFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObatAnak extends Model
{
    /** @use HasFactory<ObatAnakFactory> */
    use HasFactory;

    protected $fillable = [
        'nama_obat',
        'kategori',
        'bentuk',
        'dosis',
        'stok',
        'satuan',
        'tanggal_kedaluwarsa',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'stok' => 'integer',
            'tanggal_kedaluwarsa' => 'date',
        ];
    }
}
