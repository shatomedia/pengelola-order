<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProsesAprioriController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Proses Apriori';
        $startYear = Carbon::now()
            ->subYears(4)
            ->year;
        $endYear = $startYear + 4;
        $years = range($startYear, $endYear);
        $date = $request->input('date');
        $minSupport = $request->input('min_support');
        $minConfidence = $request->input('min_confidence');

        if ($date) {
            $products = DB::table('products')
                ->join('detail_orders', 'products.id', '=', 'detail_orders.produk_id')
                ->join('orders', 'detail_orders.order_id', '=', 'orders.id')
                ->whereYear('orders.tgl_kirim', $date) // Filter berdasarkan tahun saat ini
                ->select(
                    'products.id',
                    'products.nama',
                    DB::raw('SUM(detail_orders.qty) as total_qty'))
                ->groupBy(
                    'products.id',
                    'products.nama'
                )
                ->orderByDesc('total_qty')
                ->take(3) // Ambil 3 produk teratas
                ->get();

            $jumlahProduk = $products->count();
            $satuSet = [];
            foreach ($products as $product){
                $status[] = $jumlahProduk > 0 ? 1 : 0;
                $jumlahStatus = array_sum($status);
                $satuSet[$product->id] = ($jumlahStatus / 12) * 100;
            }

                // Memfilter produk dengan >= $minSupport
                $products = $products->filter(function($product) use ($minSupport, $satuSet) {
                    return $satuSet[$product->id] >= $minSupport;
                });
        }else{
            $products = null;
            $satuSet = null;
        }

        return view('apriories.index', compact('title','products','satuSet','years'));
    }
}
