<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use LogsActivity;

    protected $fillable = [
        'customer_id',
        'no_urut',
        'no_faktur',
        'status',
        'payment_status',
        'jumlah_dibayar',
        'nama_pembeli',
        'alamat',
        'no_hp',
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function detailOrders(): HasMany
    {
        return $this->hasMany(DetailOrder::class, 'order_id');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->no_urut = Order::lockForUpdate()->max('no_urut') + 1;

            $model->no_faktur = 'INV-' . str_pad($model->no_urut, 5, '0', STR_PAD_LEFT);
        });
    }

    public function scopeStatusOrder(Builder $query, $status): Builder
    {
        return $query->where('status', $status);
    }

    public function detailOrder(): HasOne
    {
        return $this->hasOne(DetailOrder::class, 'order_id');
    }
}
