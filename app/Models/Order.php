<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    // protected $guarded = ['id'];
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
        'keterangan'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'produk_id', 'id');
    }
}
