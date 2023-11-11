<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilApriori extends Model
{
    protected $fillable = [
        'no_urut',
        'kode_pengujian',
        'penguji',
        'nama_produk',
        'persentase_hasil_support',
        'persentase_hasil_confidence',
        'tanggal',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penguji', 'id');
    }

    public function scopeNoUrut(Builder $query, $noUrut): Builder
    {
        return $query->where('no_urut', $noUrut);
    }
}
