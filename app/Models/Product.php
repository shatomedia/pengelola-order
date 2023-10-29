<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $guarded = ['id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'kategori_id', 'id');
    }

    public function orders() : HasMany {
        return $this->hasMany(Order::class, 'produk_id');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'produk_id', 'id');
    }

    public function detailOrder(): HasOne
    {
        return $this->hasOne(DetailOrder::class, 'produk_id', 'id');
    }

    public function scopeKodeProduk(Builder $query, $kodeProduk): Builder
    {
        return $query->where('kode_produk', $kodeProduk);
    }
}
