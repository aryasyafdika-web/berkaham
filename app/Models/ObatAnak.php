<?php

namespace App\Models;

use Database\Factories\ObatAnakFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObatAnak extends Model
{
    /** @use HasFactory<ObatAnakFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'nama_obat',
        'kategori',
        'bentuk',
        'dosis',
        'stok',
        'stok_minimum',
        'satuan',
        'tanggal_kedaluwarsa',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'stok' => 'integer',
            'stok_minimum' => 'integer',
            'tanggal_kedaluwarsa' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockHistories(): HasMany
    {
        return $this->hasMany(StockHistory::class);
    }
}
