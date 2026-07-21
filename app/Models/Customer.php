<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'promo_ditawarkan',
        'promo_ditawarkan_at',
    ];

    protected $casts = [
        'promo_ditawarkan' => 'boolean',
        'promo_ditawarkan_at' => 'datetime',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
