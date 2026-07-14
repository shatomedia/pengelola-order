<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKeuanganExport;
use App\Models\Order;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LaporanKeuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:laporan-keuangan', ['only' => ['index']]);
    }

    public function index()
    {
        $title = 'Laporan Keuangan';

        $date = request()->input('date');
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        if ($date) {
            [$startDate, $endDate] = explode(' - ', $date);
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
        } else {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::create($year, $month, 1)->endOfMonth()->format('Y-m-d');
            $date = Carbon::parse($startDate)->format('d-m-Y') . ' - ' . Carbon::parse($endDate)->format('d-m-Y');
        }

        $pemasukans = Pemasukan::with('kategori', 'order')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderByDesc('tanggal')
            ->get();

        $pengeluarans = Pengeluaran::with('kategori')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderByDesc('tanggal')
            ->get();

        $ordersDenganPembayaran = Order::whereBetween('tgl_order', [$startDate, $endDate])
            ->where('jumlah_dibayar', '>', 0)
            ->orderByDesc('tgl_order')
            ->get();

        $totalPemasukan = $pemasukans->sum('jumlah') + $ordersDenganPembayaran->sum('jumlah_dibayar');
        $totalPengeluaran = $pengeluarans->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        if (request('export') == 'excel') {
            return Excel::download(new LaporanKeuanganExport($date), 'laporan-keuangan-' . $date . '.xlsx');
        }

        if (request('export') == 'pdf') {
            $pdf = Pdf::loadView('laporanKeuangan.pdf', compact(
                'pemasukans', 'pengeluarans', 'ordersDenganPembayaran', 'totalPemasukan', 'totalPengeluaran', 'saldo', 'date'
            ));

            return $pdf->download('laporan-keuangan-' . $date . '.pdf');
        }

        return view('laporanKeuangan.index', compact(
            'title', 'date', 'pemasukans', 'pengeluarans', 'ordersDenganPembayaran', 'totalPemasukan', 'totalPengeluaran', 'saldo'
        ));
    }
}
