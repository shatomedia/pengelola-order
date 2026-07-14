<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengeluaranBerulang extends Model
{
    protected $fillable = [
        'kategori_id',
        'nama',
        'jumlah_estimasi',
        'tanggal_jatuh_tempo',
        'aktif',
        'keterangan',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'kategori_id');
    }

    public function pengeluarans(): HasMany
    {
        return $this->hasMany(Pengeluaran::class, 'pengeluaran_berulang_id');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }
}
