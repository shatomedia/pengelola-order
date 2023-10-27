<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    protected $fillable = [
        'produk_id',
        'order_id',
        'qty',
        'sub_total'
    ];
}
