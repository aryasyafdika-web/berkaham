<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockHistory extends Model
{
    protected $fillable = [
        'obat_anak_id',
        'user_id',
        'jenis',
        'jumlah',
        'stok_sebelum',
        'stok_sesudah',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'stok_sebelum' => 'integer',
            'stok_sesudah' => 'integer',
        ];
    }

    public function obatAnak(): BelongsTo
    {
        return $this->belongsTo(ObatAnak::class);
    }
}
