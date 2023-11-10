<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilApriori extends Model
{
    protected $fillable = [
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
}
