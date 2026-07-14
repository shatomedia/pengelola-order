<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Pengeluaran extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'kategori_id',
        'pengeluaran_berulang_id',
        'jumlah',
        'tanggal',
        'keterangan',
        'bukti',
        'status',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'kategori_id');
    }

    public function pengeluaranBerulang(): BelongsTo
    {
        return $this->belongsTo(PengeluaranBerulang::class, 'pengeluaran_berulang_id');
    }

    public function scopeTerkonfirmasi(Builder $query): Builder
    {
        return $query->where('status', 'terkonfirmasi');
    }
}
