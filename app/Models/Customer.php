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

    /**
     * wa.me requires international format without leading '+' or '0'
     * (e.g. 0812... -> 62812...). Assumes Indonesian numbers.
     */
    public function whatsappNumber(): string
    {
        $digits = preg_replace('/\D/', '', $this->no_hp);

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        return '62' . $digits;
    }

    public function whatsappPromoUrl(): string
    {
        $message = "Halo {$this->nama}, kami dari Shatomedia mau kabari ada promo produk terbaru nih. Boleh kami info detailnya?";

        return 'https://wa.me/' . $this->whatsappNumber() . '?text=' . rawurlencode($message);
    }
}
