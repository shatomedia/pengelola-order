<?php

namespace App\Exports;

use App\Models\DetailOrder;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPenjualanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    private mixed $date;

    public function __construct($date = null)
    {
        $this->date = $date;
    }

    public function query(): \Illuminate\Database\Eloquent\Relations\Relation|\Illuminate\Database\Eloquent\Builder|\LaravelIdea\Helper\App\Models\_IH_DetailOrder_QB|\Illuminate\Database\Query\Builder
    {
        $date = $this->date;

        return DetailOrder::with('order','produk')
            ->whereHas('order', function ($query) use ($date){
                [$startDate, $endDate] = explode(' - ', $date);
                $startDate = date('Y-m-d', strtotime($startDate));
                $endDate = date('Y-m-d', strtotime($endDate));
                $query->whereBetween('tgl_kirim', [$startDate, $endDate]);
            })
            ->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Faktur',
            'Status',
            'Nama Pembeli',
            'Alamat',
            'No. HP',
            'Nama Produk',
            'Order Via',
            'Tgl. Order',
            'Tgl. Kirim',
            'Qty',
            'Sub Total',
            'Title',
            'Background',
            'Request',
            'Keterangan',
        ];
    }

    public function map($row): array
    {
        static $nomor = 0;
        $nomor++;

        $tglOrder = $row->order && $row->order->tgl_order ? Carbon::parse($row->order->tgl_order)->isoFormat('DD MMM YYYY') : '';
        $tglKirim = $row->order && $row->order->tgl_kirim ? Carbon::parse($row->order->tgl_kirim)->isoFormat('DD MMM YYYY') : '';

        return [
            $nomor,
            optional($row->order)->no_faktur,
            optional($row->order)->status,
            optional($row->order)->nama_pembeli,
            optional($row->order)->alamat,
            optional($row->order)->no_hp,
            optional($row->produk)->nama,
            optional($row->order)->order_via,
            $tglOrder,
            $tglKirim,
            $row->qty . ' item/pcs',
            'Rp ' . number_format($row->sub_total, 0,',','.'),
            optional($row->order)->title,
            optional($row->order)->background,
            optional($row->order)->request,
            optional($row->order)->keterangan,
        ];
    }
}
