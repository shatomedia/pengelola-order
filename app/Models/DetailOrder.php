<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailOrder extends Model
{
    protected $fillable = [
        'produk_id',
        'order_id',
        'qty',
        'sub_total'
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
