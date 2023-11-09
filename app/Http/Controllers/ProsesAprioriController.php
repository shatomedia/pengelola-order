<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\Product;
use App\Models\ProsesApriori;
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
            foreach (range(1,12) as $month){
                $products = DetailOrder::with('produk:id,nama')
                    ->whereHas('order', function ($query) use ($date, $month){
                        $query->whereYear('tgl_kirim', $date)
                            ->whereMonth('tgl_kirim', $month);
                    })
                    ->select('produk_id', DB::raw('SUM(qty) as total_qty'))
                    ->groupBy('produk_id')
                    ->orderByDesc('total_qty')
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
                foreach (range(1,12) as $month){
                    $proApriori = ProsesApriori::join('products','proses_aprioris.product_id', 'products.id')
                        ->join('detail_orders','detail_orders.produk_id', '=', 'products.id')
                        ->join('orders','orders.id', '=', 'detail_orders.id')
                        ->whereYear('proses_aprioris.date', $date)
                        ->whereMonth('proses_aprioris.date', $month)
                        ->where('products.id', $productApriori->product_id)
                        ->first();

                    $productCounts[] = $proApriori ? 1 : 0;
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

            /*hasil 2 setitem*/
            $proses2SetItems = $this->proses2SetItem($satuSetItem);
            $filtered2NameCombinations = $proses2SetItems['filteredNameCombinations'];
            $filtered2Names = $proses2SetItems['filteredNames'];
            $total2YesPerIndex = $proses2SetItems['totalYesPerIndex'];
            $persentase2SetItems = $proses2SetItems['persentase2SetItems'];

            /*hasil 3 set item*/
            $proses3SetItems = $this->proses3SetItem($satuSetItem);
            $filtered3NameCombinations = $proses3SetItems['filteredNameCombinations'];
            $filtered3Names = $proses3SetItems['filteredNames'];
            $total3YesPerIndex = $proses3SetItems['totalYesPerIndex'];
            $persentase3SetItems = $proses3SetItems['persentase3SetItems'];
        }else{
            $products = null;
            $satuSetItem = null;
            /*2 set items*/
            $filtered2NameCombinations = null;
            $filtered2Names = null;
            $total2YesPerIndex = null;
            $persentase2SetItems = null;
            /*3 set items*/
            $filtered3NameCombinations = null;
            $filtered3Names = null;
            $total3YesPerIndex = null;
            $persentase3SetItems = null;
        }

        return view('apriories.index', compact(
            'title','products','years','satuSetItem',
            'filtered2NameCombinations','filtered2Names','total2YesPerIndex','persentase2SetItems',
            'filtered3NameCombinations','filtered3Names','total3YesPerIndex','persentase3SetItems'
        ));
    }

    public function proses2SetItem($satuSetItem)
    {
        $date = \request('date');
        $minSupport = \request('min_support');

        $productIds = [];
        foreach ($satuSetItem as $item) {
            $productIds[] = $item['product_id'];
        }

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

            $combination2Sets = array_merge($combination2Sets, $combinations);

            $results = [];

            foreach ($combinations as $combination) {
                $transaksiItem1 = ProsesApriori::join('products','proses_aprioris.product_id', 'products.id')
                    ->join('detail_orders','detail_orders.produk_id', '=', 'products.id')
                    ->join('orders','orders.id', '=', 'detail_orders.id')
                    ->whereYear('proses_aprioris.date', $date)
                    ->whereMonth('proses_aprioris.date', $month)
                    ->where('products.id', $combination['product_id_1'])
                    ->first();

                $transaksiItem2 = ProsesApriori::join('products','proses_aprioris.product_id', 'products.id')
                    ->join('detail_orders','detail_orders.produk_id', '=', 'products.id')
                    ->join('orders','orders.id', '=', 'detail_orders.id')
                    ->whereYear('proses_aprioris.date', $date)
                    ->whereMonth('proses_aprioris.date', $month)
                    ->where('products.id', $combination['product_id_2'])
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

        $filteredNameCombinations = array_filter($uniqueCombinations, function($combination, $index) use ($persentase2SetItems, $minSupport) {
            return $persentase2SetItems[$index] >= $minSupport;
        }, ARRAY_FILTER_USE_BOTH);

        $filteredNames = array_map(function($combination) {
            return [
                'product_name_1' => $combination['product_name_1'] . ' => ',
                'product_name_2' => $combination['product_name_2'],
            ];
        }, $filteredNameCombinations);

        return compact('filteredNames', 'totalYesPerIndex', 'persentase2SetItems', 'filteredNameCombinations');
    }

    public function proses3SetItem($satuSetItem)
    {
        $date = \request('date');
        $minSupport = \request('min_support');

        $productIds = [];
        foreach ($satuSetItem as $item) {
            $productIds[] = $item['product_id'];
        }

        $product3Sets = [];
        $combination3Sets = [];

        foreach (range(1, 12) as $month) {
            $combinations = [];

            $productCount = count($productIds);

            for ($i = 0; $i < $productCount - 2; $i++) {
                for ($j = $i + 1; $j < $productCount - 1; $j++) {
                    for ($k = $j + 1; $k < $productCount; $k++) {
                        $productId1 = $productIds[$i];
                        $productId2 = $productIds[$j];
                        $productId3 = $productIds[$k];

                        $product1 = Product::find($productId1);
                        $product2 = Product::find($productId2);
                        $product3 = Product::find($productId3);

                        $combinations[] = [
                            'product_id_1' => $productId1,
                            'product_name_1' => $product1->nama,
                            'product_id_2' => $productId2,
                            'product_name_2' => $product2->nama,
                            'product_id_3' => $productId3,
                            'product_name_3' => $product3->nama,
                        ];
                    }
                }
            }

            $combination3Sets = array_merge($combination3Sets, $combinations);

            $results = [];

            foreach ($combinations as $combination) {
                $transaksiItem1 = ProsesApriori::join('products','proses_aprioris.product_id', 'products.id')
                    ->join('detail_orders','detail_orders.produk_id', '=', 'products.id')
                    ->join('orders','orders.id', '=', 'detail_orders.id')
                    ->whereYear('proses_aprioris.date', $date)
                    ->whereMonth('proses_aprioris.date', $month)
                    ->where('products.id', $combination['product_id_1'])
                    ->first();

                $transaksiItem2 = ProsesApriori::join('products','proses_aprioris.product_id', 'products.id')
                    ->join('detail_orders','detail_orders.produk_id', '=', 'products.id')
                    ->join('orders','orders.id', '=', 'detail_orders.id')
                    ->whereYear('proses_aprioris.date', $date)
                    ->whereMonth('proses_aprioris.date', $month)
                    ->where('products.id', $combination['product_id_2'])
                    ->first();

                $transaksiItem3 = ProsesApriori::join('products','proses_aprioris.product_id', 'products.id')
                    ->join('detail_orders','detail_orders.produk_id', '=', 'products.id')
                    ->join('orders','orders.id', '=', 'detail_orders.id')
                    ->whereYear('proses_aprioris.date', $date)
                    ->whereMonth('proses_aprioris.date', $month)
                    ->where('products.id', $combination['product_id_3'])
                    ->first();

                $results[] = $transaksiItem1 && $transaksiItem2 && $transaksiItem3 ? 'Y' : 'N';
            }

            $product3Sets[$month] = $results;
        }

        $uniqueCombinations = array_unique($combination3Sets, SORT_REGULAR);

        // Menghitung jumlah "Y" per kombinasi produk dengan indeks yang sama selama 12 bulan
        $totalYesPerIndex = array_fill(0, count($combinations), 0);

        foreach ($product3Sets as $monthResults) {
            foreach ($monthResults as $index => $result) {
                if ($result === 'Y') {
                    $totalYesPerIndex[$index]++;
                }
            }
        }

        $persentase3SetItems = [];
        foreach ($totalYesPerIndex as $persentaseTotalYes){
            $totalStatus = (int) $persentaseTotalYes;
            $persentase3SetItems[] = ($totalStatus / 12) * 100;
        }

        $filteredNameCombinations = array_filter($uniqueCombinations, function($combination, $index) use ($persentase3SetItems, $minSupport) {
            return $persentase3SetItems[$index] >= $minSupport;
        }, ARRAY_FILTER_USE_BOTH);

        $filteredNames = array_map(function($combination) {
            return [
                'product_name_1' => $combination['product_name_1'] . ' => ',
                'product_name_2' => $combination['product_name_2'] . ' => ',
                'product_name_3' => $combination['product_name_3'],
            ];
        }, $filteredNameCombinations);

        return compact('filteredNames', 'totalYesPerIndex', 'persentase3SetItems', 'filteredNameCombinations');
    }
}
