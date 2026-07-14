<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanKeuanganExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected mixed $date;

    public function __construct($date = null)
    {
        $this->date = $date;
    }

    public function collection(): Collection
    {
        [$startDate, $endDate] = explode(' - ', $this->date);
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));

        $pemasukans = Pemasukan::with('kategori')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get()
            ->map(function ($row) {
                $row->jenis = 'Pemasukan';
                $row->keterangan_tampil = $row->sumber;

                return $row;
            });

        $pengeluarans = Pengeluaran::with('kategori')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get()
            ->map(function ($row) {
                $row->jenis = 'Pengeluaran';
                $row->keterangan_tampil = $row->keterangan;

                return $row;
            });

        $ordersDenganPembayaran = Order::whereBetween('tgl_order', [$startDate, $endDate])
            ->where('jumlah_dibayar', '>', 0)
            ->get()
            ->map(function ($row) {
                $row->jenis = 'Pemasukan (Order)';
                $row->tanggal = $row->tgl_order;
                $row->jumlah = $row->jumlah_dibayar;
                $row->kategori = null;
                $row->keterangan_tampil = $row->no_faktur . ' - ' . $row->nama_pembeli;

                return $row;
            });

        return $pemasukans->concat($pengeluarans)->concat($ordersDenganPembayaran)->sortByDesc('tanggal')->values();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Jenis',
            'Kategori',
            'Sumber/Keterangan',
            'Jumlah',
        ];
    }

    public function map($row): array
    {
        static $nomor = 0;
        $nomor++;

        return [
            $nomor,
            Carbon::parse($row->tanggal)->isoFormat('DD MMM YYYY'),
            $row->jenis,
            optional($row->kategori)->nama_kategori,
            $row->keterangan_tampil,
            'Rp ' . number_format($row->jumlah, 0, ',', '.'),
        ];
    }
}
