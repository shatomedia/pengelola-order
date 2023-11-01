<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
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

            $satuSetItem = [];
            $totalTransaksi = [];

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
                    $satuSetItem[$productApriori->product->nama] = $persentaseMinSupport;
                }

                $totalTransaksi[$productApriori->product->nama] = $totalStatus1;

            }
        }else{
            $products = null;
            $satuSetItem = null;
            $totalTransaksi = null;
        }

        return view('apriories.index', compact('title','products','years','satuSetItem','totalTransaksi'));
    }
}
