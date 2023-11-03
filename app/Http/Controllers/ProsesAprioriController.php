<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\Product;
use App\Models\ProsesApriori;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
            foreach (range(1,12) as $month){
                $products = DetailOrder::with('produk:id,nama')
                    ->whereHas('order', function ($query) use ($date, $month){
                        $query->whereYear('tgl_kirim', $date)
                            ->whereMonth('tgl_kirim', $month);
                    })
                    ->orderByDesc('qty')
                    ->take(3)
                    ->get();

                if ($products->count() > 0){
                    $tanggal = $date . '-' . $month . '-' . date('d');
                    foreach ($products as $product){
                        $prosesApriori = ProsesApriori::whereYear('date', $date)
                            ->whereMonth('date', $month)
                            ->productId($product->produk_id)
                            ->firstOrNew();
                        $prosesApriori->product_id = $product->produk_id;
                        $prosesApriori->date = $tanggal;
                        $prosesApriori->save();
                    }
                }
            }

            $productAprioris = ProsesApriori::whereYear('date', $date)
                ->select('product_id')
                ->groupBy('product_id')
                ->get();

            /*hasil 1 setitem*/
            $satuSetItem = [];

            foreach ($productAprioris as $productApriori){
                $productCounts = [];
                foreach (range(1, 12) as $month){
                    $detailOrder = DetailOrder::productId($productApriori->product_id)
                        ->whereHas('order', function ($query) use ($date, $month){
                            $query->whereYear('tgl_kirim', $date)
                                ->whereMonth('tgl_kirim', $month);
                        })
                        ->first();

                    $productCounts[] = $detailOrder ? 1 : 0;
                }

                $totalStatus1 = array_sum($productCounts);
                $persentaseMinSupport = ($totalStatus1 / 12) * 100;

                if ($persentaseMinSupport > $minSupport){
                    $satuSetItem[] = [
                        'product_id' => $productApriori->product_id,
                        'product_name' => $productApriori->product->nama,
                        'total_transaksi' => $totalStatus1,
                        'persentase' => $persentaseMinSupport,
                    ];
                }
            }

            $jumlahData = count($satuSetItem);

            /*hasil 2 setitem*/
            if ($jumlahData > 0){
                /*$productApriori2Sets = ProsesApriori::whereIn('product_id', $productIds)
                    ->select('product_id')
                    ->selectRaw('YEAR(date) as year, MONTH(date) as month')
                    ->groupBy('product_id', 'year', 'month')
                    ->get()->toArray();*/
                /*$product2Sets = ProsesApriori::whereIn('product_id', $productIds)
                        ->whereYear('date', $date)
                        ->whereMonth('date', $month)
                        ->select('product_id')
                        ->groupBy('product_id')
                        ->get()->toArray();*/

                /*$product2Sets = [];
                foreach (range(1,12) as $month){
                    $product2Sets[$month] = ProsesApriori::whereIn('product_id', $productIds)
                        ->whereYear('date', $date)
                        ->whereMonth('date', $month)
                        ->select('product_id')
                        ->selectRaw('YEAR(date) as year, MONTH(date) as month')
                        ->groupBy('product_id', 'year', 'month')
                        ->get()->toArray();
                }
                dd($product2Sets);*/

                /*$productIds = [];
                foreach ($satuSetItem as $item) {
                    $productIds[] = $item['product_id'];
                }

                $product2Sets = [];
                foreach (range(1, 12) as $month) {
                    for ($i = 0; $i < count($productIds) - 1; $i++) {
                        for ($j = $i + 1; $j < count($productIds); $j++) {
                            $productId1 = $productIds[$i];
                            $productId2 = $productIds[$j];

                            $product2Sets[$month][] = ProsesApriori::whereIn('product_id', [$productId1, $productId2])
                                ->whereYear('date', $date)
                                ->whereMonth('date', $month)
                                ->join('products', 'proses_aprioris.product_id', '=', 'products.id')
                                ->select([
                                    'product_id',
                                    'products.nama'
                                ])
                                ->selectRaw('YEAR(date) as year, MONTH(date) as month')
                                ->groupBy('product_id', 'products.nama', 'year', 'month')
                                ->get()->toArray();
                        }
                    }
                }

                dd($product2Sets, $productIds);*/
                $productIds = [];
                foreach ($satuSetItem as $item) {
                    $productIds[] = $item['product_id'];
                }

                $product2Sets = [];
                foreach (range(1, 12) as $month) {
                    $product2Sets[$month] = [];
                    $productCount = count($productIds);

                    foreach ($productIds as $i => $productId1) {
                        for ($j = $i + 1; $j < $productCount; $j++) {
                            $productId2 = $productIds[$j];

                            $product2Sets[$month][] = ProsesApriori::whereIn('product_id', [$productId1, $productId2])
                                ->whereYear('date', $date)
                                ->whereMonth('date', $month)
                                ->join('products', 'proses_aprioris.product_id', '=', 'products.id')
                                ->select([
                                    'product_id',
                                    'products.nama'
                                ])
                                ->selectRaw('YEAR(date) as year, MONTH(date) as month')
                                ->groupBy('product_id', 'products.nama', 'year', 'month')
                                ->get()->toArray();
                        }
                    }
                }

                dd($product2Sets);
            }
        }else{
            $products = null;
            $satuSetItem = null;
        }

        return view('apriories.index', compact('title','products','years','satuSetItem'));
    }
}
