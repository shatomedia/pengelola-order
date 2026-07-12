<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPenjualanExport;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        $title = 'Laporan Penjualan';
        $listStatus = Order::select('status')
            ->distinct()
            ->get();

        $date = \request()->input('date');
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $productid = \request()->input('product_id');
        $status = \request()->input('status');

        $jumlahQty = array();
        $monthList = array();
        if ($date && $productid){
            list($startDate, $endDate) = explode(' - ', $date);

            // Mengonversi string tanggal menjadi objek Carbon
            $startDateTime = Carbon::createFromFormat('d-m-Y', $startDate);
            $endDateTime = Carbon::createFromFormat('d-m-Y', $endDate);

            // Mengatur tanggal awal ke awal bulan
            $startDateTime->startOfMonth();

            // Membuat rentang bulan dari tanggal awal hingga tanggal akhir
            $period = Carbon::parse($startDateTime)->monthsUntil($endDateTime);

            // Iterasi melalui rentang bulan dan lakukan sesuatu dengan setiap bulan
            foreach ($period as $rangeDate) {
                $tahun = Carbon::parse($rangeDate)->year;
                $bulan = Carbon::parse($rangeDate)->month;
                $jumlahQty[] = DetailOrder::whereHas('order', function ($query) use ($tahun, $bulan, $status){
                        $query->whereYear('tgl_order', $tahun)
                            ->whereMonth('tgl_order', $bulan)
                            ->when($status, function ($query) use ($status){
                                $query->where('status', $status);
                            });
                    })
                    ->productId($productid)
                    ->sum('qty');
                $monthList[] = Carbon::parse($rangeDate)->isoFormat('MMMM YYYY');
            }
        }

        $product = Product::find($productid);

        $orders = Order::withCount('detailOrders')
            ->with('detailOrder')
            ->when($date, function ($query) use ($date){
                [$startDate, $endDate] = explode(' - ', $date);
                $startDate = date('Y-m-d', strtotime($startDate));
                $endDate = date('Y-m-d', strtotime($endDate));
                $query->whereBetween('tgl_order', [$startDate, $endDate]);
            }, function ($query) use ($year, $month){
                $query->whereYear('tgl_order', $year)
                    ->whereMonth('tgl_order', $month);
            })
            ->when($productid != null, function ($query) use ($productid){
                $query->whereHas('detailOrder', function ($query) use ($productid){
                    $query->where('produk_id', $productid);
                });
            })
            ->when($status != null, function ($query) use ($status){
                $query->where('status', $status);
            })
            ->paginate(10);

        if (request('export') == 'true'){
            return Excel::download(New LaporanPenjualanExport($date, $productid, $status), 'laporan-penjualan-' . $date . '.xlsx');
        }

        return view('laporanPenjualan.index', compact('title','date','orders','product','jumlahQty','monthList','listStatus'));
    }
}
