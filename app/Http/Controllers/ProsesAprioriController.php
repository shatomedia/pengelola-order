<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\Product;
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
                ->whereYear('orders.tgl_kirim', $date)
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

            /*$status = [];
            foreach ($products as $product){
                foreach (range(1,12) as $month) {
                    $data = DetailOrder::where('produk_id', $product->id)
                        ->whereHas('order', function ($query) use ($date, $month){
                            $query->whereYear('tgl_kirim', $date)
                                ->whereMonth('tgl_kirim', $month);
                        })
                        ->first();
                    $status[$month][] = $data ? 1 : 0;
                }
            }
            dd($status);*/
            /*$data = [];*/
            /*foreach (range(1,12) as $month) {
                $data[] = DB::table('products')
                    ->join('detail_orders', 'products.id', '=', 'detail_orders.produk_id')
                    ->join('orders', 'detail_orders.order_id', '=', 'orders.id')
                    ->whereYear('orders.tgl_kirim', $date)
                    ->whereMonth('orders.tgl_kirim', $month)
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
            }*/
            $cek = [];
            $data = DB::table('products')
                ->join('detail_orders', 'products.id', '=', 'detail_orders.produk_id')
                ->join('orders', 'detail_orders.order_id', '=', 'orders.id')
                ->whereYear('orders.tgl_kirim', $date)
                ->select(
                    'products.id',
                    'products.nama',
                    DB::raw('SUM(detail_orders.qty) as total_qty'))
                ->groupBy(
                    'products.id',
                    'products.nama'
                )
                ->orderByDesc('total_qty')
                /*->take(3)*/ // Ambil 3 produk teratas
                ->get();

            foreach ($data as $item){
                foreach (range(1,12) as $month){
                    $cek[$month][] = DetailOrder::where('produk_id', $item->id)
                        ->whereHas('order', function ($query) use ($date, $month){
                            $query->whereYear('tgl_kirim', $date)
                                ->whereMonth('tgl_kirim', $month);
                        })
                        ->orderByDesc('qty')
                        ->take(3)
                        ->get()->toArray();
                }
            }

            dd($cek);

            /*$jumlahProduk = $products->count();

            $satuSet = [];
            foreach ($products as $product){
                $status[] = $jumlahProduk > 0 ? 1 : 0;
                $jumlahStatus = array_sum($status);
                $satuSet[$product->id] = ($jumlahStatus / 12) * 100;
            }

            // Memfilter produk dengan >= $minSupport
            $products = $products->filter(function($product) use ($minSupport, $satuSet) {
                return $satuSet[$product->id] >= $minSupport;
            });*/
        }else{
            $products = null;
            $satuSet = null;
        }

        return view('apriories.index', compact('title','products','satuSet','years'));
    }
}
