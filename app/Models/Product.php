<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function category()
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
}
