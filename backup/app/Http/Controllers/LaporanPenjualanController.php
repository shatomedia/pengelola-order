<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPenjualanExport;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        $title = 'Laporan Penjualan';
        $date = \request()->input('date');
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        $orders = Order::withCount('detailOrders')
            ->when($date, function ($query) use ($date){
                [$startDate, $endDate] = explode(' - ', $date);
                $startDate = date('Y-m-d', strtotime($startDate));
                $endDate = date('Y-m-d', strtotime($endDate));
                $query->whereBetween('tgl_kirim', [$startDate, $endDate]);
            }, function ($query) use ($year, $month){
                $query->whereYear('tgl_kirim', $year)
                    ->whereMonth('tgl_kirim', $month);
            })
            ->paginate(10);

        if (request('export') == 'true'){
            return Excel::download(New LaporanPenjualanExport($date), 'laporan-penjualan-' . $date . '.xlsx');
        }

        return view('laporanPenjualan.index', compact('title','date','orders'));
    }
}
