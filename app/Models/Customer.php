<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

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
        $recommended = $this->recommendedProductName();

        $message = $recommended
            ? "Halo {$this->nama}, kami dari Shatomedia mau kabari ada promo produk terbaru nih. Berdasarkan pembelian Anda sebelumnya, produk *{$recommended}* mungkin cocok untuk Anda. Boleh kami info detailnya?"
            : "Halo {$this->nama}, kami dari Shatomedia mau kabari ada promo produk terbaru nih. Boleh kami info detailnya?";

        return 'https://wa.me/' . $this->whatsappNumber() . '?text=' . rawurlencode($message);
    }

    /**
     * Distinct products this customer has bought before, newest order first.
     */
    public function boughtProductNames(): array
    {
        return DetailOrder::whereHas('order', fn ($query) => $query->where('customer_id', $this->id))
            ->with('produk:id,nama')
            ->get()
            ->pluck('produk.nama')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Cross-sell suggestion from the latest Apriori run: the highest-confidence
     * rule "{a product this customer already bought} => {recommended product}".
     */
    public function recommendedProductName(): ?string
    {
        $boughtProducts = $this->boughtProductNames();

        if (empty($boughtProducts)) {
            return null;
        }

        $latestBatch = HasilApriori::max('no_urut');

        if (! $latestBatch) {
            return null;
        }

        $rule = HasilApriori::noUrut($latestBatch)
            ->where(function ($query) use ($boughtProducts) {
                foreach ($boughtProducts as $productName) {
                    $query->orWhere('nama_produk', 'LIKE', $productName . ' =>%');
                }
            })
            ->orderByDesc('persentase_hasil_confidence')
            ->first();

        if (! $rule) {
            return null;
        }

        [, $recommended] = array_pad(explode('=>', $rule->nama_produk, 2), 2, null);

        return $recommended ? trim($recommended) : null;
    }

    public function lastOrderDate(): ?Carbon
    {
        $tglOrder = $this->orders()->max('tgl_order');

        return $tglOrder ? Carbon::parse($tglOrder) : null;
    }

    public function isChurned(): bool
    {
        $lastOrder = $this->lastOrderDate();

        return ! $lastOrder || $lastOrder->lt(now()->subDays(90));
    }
}
