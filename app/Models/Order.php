<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'status',
        'nama_pembeli',
        'alamat',
        'no_hp',
        'produk_id',
        'order_via',
        'tgl_order',
        'tgl_kirim',
        'title',
        'background',
        'request',
        'keterangan',
        'total_qty',
        'total_harga_jual'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'produk_id', 'id');
    }

    public function detailOrders(): HasMany
    {
        return $this->hasMany(DetailOrder::class, 'order_id');
    }
}
