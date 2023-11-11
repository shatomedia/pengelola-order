<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Carbon;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        $title = "Laporan Penjualan";
        $date = \request()->input('date');
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        $orders = Order::when($date, function ($query) use ($date){
            [$startDate, $endDate] = explode(' - ', $date);
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
            $query->whereBetween('tgl_kirim', [$startDate, $endDate]);
        }, function ($query) use ($year, $month){
            $query->whereYear('tgl_kirim', $year)
                ->whereMonth('tgl_kirim', $month);
        })
            ->paginate(10);

        return view('laporanPenjualan.index', compact('title','date','orders'));
    }
}
