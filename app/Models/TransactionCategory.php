<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionCategory extends Model
{
    protected $fillable = [
        'nama_kategori',
        'jenis',
        'deskripsi',
    ];

    public function pemasukans(): HasMany
    {
        return $this->hasMany(Pemasukan::class, 'kategori_id');
    }

    public function pengeluarans(): HasMany
    {
        return $this->hasMany(Pengeluaran::class, 'kategori_id');
    }

    public function scopeJenis(Builder $query, $jenis): Builder
    {
        return $query->where('jenis', $jenis);
    }
}
