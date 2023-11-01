<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProsesApriori extends Model
{
    protected $fillable = [
        'product_id',
        'date',
    ];

    public function scopeProductId(Builder $query, $productId): Builder
    {
        return $query->where('product_id', $productId);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
