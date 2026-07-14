<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Pengeluaran extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'kategori_id',
        'jumlah',
        'tanggal',
        'keterangan',
        'bukti',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'kategori_id');
    }
}
