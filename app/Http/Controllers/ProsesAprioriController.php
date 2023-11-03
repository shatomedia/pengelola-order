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
                $productIds = [];
                foreach ($satuSetItem as $item) {
                    $productIds[] = $item['product_id'];
                }

                /*================= hasil proses pertama ===================*/
                /*$product2Sets = [];
                foreach (range(1, 12) as $month) {
                    $combinations = [];

                    $productCount = count($productIds);

                    for ($i = 0; $i < $productCount - 1; $i++) {
                        for ($j = $i + 1; $j < $productCount; $j++) {
                            $productId1 = $productIds[$i];
                            $productId2 = $productIds[$j];

                            $product1 = Product::find($productId1);
                            $product2 = Product::find($productId2);

                            $combinations[] = [
                                'product_id_1' => $productId1,
                                'product_name_1' => $product1->nama,
                                'product_id_2' => $productId2,
                                'product_name_2' => $product2->nama,
                            ];
                        }
                    }

                    $product2Sets[$month] = $combinations;
                }

                dd($product2Sets);*/
                /*================= akhir hasil proses pertama ===================*/

                /*================= hasil proses kedua ===========================*/
                /*$product2Sets = [];
                foreach (range(1, 12) as $month) {
                    $combinations = [];

                    $productCount = count($productIds);

                    for ($i = 0; $i < $productCount - 1; $i++) {
                        for ($j = $i + 1; $j < $productCount; $j++) {
                            $productId1 = $productIds[$i];
                            $productId2 = $productIds[$j];

                            $product1 = Product::find($productId1);
                            $product2 = Product::find($productId2);

                            $combinations[] = [
                                'product_id_1' => $productId1,
                                'product_name_1' => $product1->nama,
                                'product_id_2' => $productId2,
                                'product_name_2' => $product2->nama,
                            ];
                        }
                    }

                    $results = [];

                    foreach ($combinations as $combination) {
                        $transaksiItem1 = DetailOrder::productId($combination['product_id_1'])
                            ->whereHas('order', function ($query) use ($date, $month){
                                $query->whereYear('tgl_kirim', $date)
                                    ->whereMonth('tgl_kirim', $month);
                            })
                            ->first();

                        $transaksiItem2 = DetailOrder::productId($combination['product_id_2'])
                            ->whereHas('order', function ($query) use ($date, $month){
                                $query->whereYear('tgl_kirim', $date)
                                    ->whereMonth('tgl_kirim', $month);
                            })
                            ->first();

                        $results[] = $transaksiItem1 && $transaksiItem2 ? 'Y' : 'N';
                    }

                    $product2Sets[$month] = $results;
                }

                dd($product2Sets);*/
                /*==================== akhir hasil proses kedua ====================*/

                /*==================== hasil proses ketiga ==========================*/
                /*$product2Sets = [];
                $totalYesPerMonth = [];

                foreach (range(1, 12) as $month) {
                    $combinations = [];
                    $productCount = count($productIds);

                    for ($i = 0; $i < $productCount - 1; $i++) {
                        for ($j = $i + 1; $j < $productCount; $j++) {
                            $productId1 = $productIds[$i];
                            $productId2 = $productIds[$j];

                            $product1 = Product::find($productId1);
                            $product2 = Product::find($productId2);

                            $combinations[] = [
                                'product_id_1' => $productId1,
                                'product_name_1' => $product1->nama,
                                'product_id_2' => $productId2,
                                'product_name_2' => $product2->nama,
                            ];
                        }
                    }

                    $results = [];

                    foreach ($combinations as $combination) {
                        $transaksiItem1 = DetailOrder::productId($combination['product_id_1'])
                            ->whereHas('order', function ($query) use ($date, $month) {
                                $query->whereYear('tgl_kirim', $date)
                                    ->whereMonth('tgl_kirim', $month);
                            })
                            ->first();

                        $transaksiItem2 = DetailOrder::productId($combination['product_id_2'])
                            ->whereHas('order', function ($query) use ($date, $month) {
                                $query->whereYear('tgl_kirim', $date)
                                    ->whereMonth('tgl_kirim', $month);
                            })
                            ->first();

                        $results[] = $transaksiItem1 && $transaksiItem2 ? 'Y' : 'N';
                    }

                    $product2Sets[$month] = $results;

                    // Menghitung jumlah "Y" tiap bulan
                    $totalYesPerMonth[$month] = count(array_filter($results, function ($result) {
                        return $result === 'Y';
                    }));
                }

                dd($product2Sets, $totalYesPerMonth);*/
                /*=================== akhir hasil proses ketiga ===================*/

                /*=================== hasil proses keempat ======================*/
                $product2Sets = [];
                $combination2Sets = [];

                foreach (range(1, 12) as $month) {
                    $combinations = [];

                    $productCount = count($productIds);

                    for ($i = 0; $i < $productCount - 1; $i++) {
                        for ($j = $i + 1; $j < $productCount; $j++) {
                            $productId1 = $productIds[$i];
                            $productId2 = $productIds[$j];

                            $product1 = Product::find($productId1);
                            $product2 = Product::find($productId2);

                            $combinations[] = [
                                'product_id_1' => $productId1,
                                'product_name_1' => $product1->nama,
                                'product_id_2' => $productId2,
                                'product_name_2' => $product2->nama,
                            ];
                        }
                    }

                    /*$combination2Sets[$month] = $combinations;
                    $kombinasi = $combinations;*/
                    $combination2Sets = array_merge($combination2Sets, $combinations);

                    $results = [];

                    foreach ($combinations as $combination) {
                        $transaksiItem1 = DetailOrder::productId($combination['product_id_1'])
                            ->whereHas('order', function ($query) use ($date, $month){
                                $query->whereYear('tgl_kirim', $date)
                                    ->whereMonth('tgl_kirim', $month);
                            })
                            ->first();

                        $transaksiItem2 = DetailOrder::productId($combination['product_id_2'])
                            ->whereHas('order', function ($query) use ($date, $month){
                                $query->whereYear('tgl_kirim', $date)
                                    ->whereMonth('tgl_kirim', $month);
                            })
                            ->first();

                        $results[] = $transaksiItem1 && $transaksiItem2 ? 'Y' : 'N';
                    }

                    $product2Sets[$month] = $results;
                }

                $uniqueCombinations = array_unique($combination2Sets, SORT_REGULAR);

                // Menghitung jumlah "Y" per kombinasi produk dengan indeks yang sama selama 12 bulan
                $totalYesPerIndex = array_fill(0, count($combinations), 0);

                foreach ($product2Sets as $monthResults) {
                    foreach ($monthResults as $index => $result) {
                        if ($result === 'Y') {
                            $totalYesPerIndex[$index]++;
                        }
                    }
                }

                $persentase2SetItems = [];
                foreach ($totalYesPerIndex as $persentaseTotalYes){
                    $totalStatus = (int) $persentaseTotalYes;
                    $persentase2SetItems[] = ($totalStatus / 12) * 100;
                }

                $uniqueNames = array_map(function($combination) {
                    return [
                        'product_name_1' => $combination['product_name_1'] . ' => ',
                        'product_name_2' => $combination['product_name_2'],
                    ];
                }, $uniqueCombinations);

                /*dd($uniqueNames, $product2Sets, $totalYesPerIndex, $persentase2SetItems);*/
                /*================ akhir hasil proses keempat =====================*/
            }else {
                $uniqueNames = null;
                $totalYesPerIndex = null;
                $persentase2SetItems = null;
            }
        }else{
            $products = null;
            $satuSetItem = null;
            $uniqueNames = null;
            $totalYesPerIndex = null;
            $persentase2SetItems = null;
        }

        return view('apriories.index', compact('title','products','years','satuSetItem','uniqueNames','totalYesPerIndex','persentase2SetItems'));
    }
}
