<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeOrderId(Builder $query, $orderId): Builder
    {
        return $query->where('order_id', $orderId);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
