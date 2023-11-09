<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductItemSet extends Model
{
    protected $fillable = [
        'kode_item_set',
        'total_transaksi',
        'persentase',
        'kategori',
        'tahun',
    ];

    public function scopeKodeItemSet(Builder $query, $kodeItemSet): Builder
    {
        return $query->where('kode_item_set', $kodeItemSet);
    }
}
