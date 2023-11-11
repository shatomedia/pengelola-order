<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\HasilApriori;
use App\Models\Product;
use App\Models\ProductItemSet;
use App\Models\ProsesApriori;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProsesAprioriController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Proses Apriori';
        $startYear = Carbon::now()
            ->subYears(4)
            ->year;
        $endYear = $startYear + 3;
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

                /*simpan ke database untuk kategori 1 itemset*/
                $productName1ItemSet = Product::where('id', $productApriori->product_id)
                    ->pluck('nama');
                $productName1ItemSetSlug = Str::slug($productName1ItemSet);
                $productItemSet = ProductItemSet::whereYear('tahun', $date)
                    ->where('kode_item_set', $productName1ItemSetSlug)
                    ->firstOrNew();
                $productItemSet->kode_item_set = $productName1ItemSetSlug;
                $productItemSet->total_transaksi = $totalStatus1;
                $productItemSet->persentase = $persentaseMinSupport;
                $productItemSet->kategori = '1_itemset';
                $productItemSet->tahun = $date;
                $productItemSet->save();
                /*===========*/
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

            /*hasil 4 set item*/
            $proses4SetItems = $this->proses4SetItem($satuSetItem);
            $filtered4NameCombinations = $proses4SetItems['filteredNameCombinations'];
            $filtered4Names = $proses4SetItems['filteredNames'];
            $total4YesPerIndex = $proses4SetItems['totalYesPerIndex'];
            $persentase4SetItems = $proses4SetItems['persentase4SetItems'];

            /*proses confidence*/
            $confidence = $this->confidence($date, $minConfidence, $satuSetItem);

            $tableConfidenceItemSets = $confidence['tableConfidenceItemSets'];
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

            /*4 set items*/
            $filtered4NameCombinations = null;
            $filtered4Names = null;
            $total4YesPerIndex = null;
            $persentase4SetItems = null;

            /*confidence 2 item sets*/
            $tableConfidenceItemSets = null;
        }

        return view('apriories.index', compact(
            'title','products','years','satuSetItem',
            'filtered2NameCombinations','filtered2Names','total2YesPerIndex','persentase2SetItems',
            'filtered3NameCombinations','filtered3Names','total3YesPerIndex','persentase3SetItems',
            'filtered4NameCombinations','filtered4Names','total4YesPerIndex','persentase4SetItems',
            'tableConfidenceItemSets'
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

        /*simpan ke database untuk kategori 2 itemset*/
        foreach ($filteredNameCombinations as $key => $filteredNameCombination){
            $mergedName = [];
            foreach ($filteredNames as $index => $namesArray) {
                $mergedName[$index] = Str::slug($namesArray['product_name_1'] . $namesArray['product_name_2']);
            }

            $productItemSet = ProductItemSet::whereYear('tahun', $date)
                ->where('kode_item_set', $mergedName[$key])
                ->firstOrNew();
            $productItemSet->kode_item_set = $mergedName[$key];
            $productItemSet->total_transaksi = $totalYesPerIndex[$key];
            $productItemSet->persentase = $persentase2SetItems[$key];
            $productItemSet->kategori = '2_itemset';
            $productItemSet->tahun = $date;
            $productItemSet->save();
        }
        /*===========*/

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

        /*simpan ke database untuk kategori 3 itemset*/
        foreach ($filteredNameCombinations as $key => $filteredNameCombination){
            $mergedName = [];
            foreach ($filteredNames as $index => $namesArray) {
                $mergedName[$index] = Str::slug($namesArray['product_name_1'] . $namesArray['product_name_2'] . $namesArray['product_name_3']);
            }

            $productItemSet = ProductItemSet::whereYear('tahun', $date)
                ->where('kode_item_set', $mergedName[$key])
                ->firstOrNew();
            $productItemSet->kode_item_set = $mergedName[$key];
            $productItemSet->total_transaksi = $totalYesPerIndex[$key];
            $productItemSet->persentase = $persentase3SetItems[$key];
            $productItemSet->kategori = '3_itemset';
            $productItemSet->tahun = $date;
            $productItemSet->save();
        }
        /*===========*/

        return compact('filteredNames', 'totalYesPerIndex', 'persentase3SetItems', 'filteredNameCombinations');
    }

    public function proses4SetItem($satuSetItem)
    {
        $date = \request('date');
        $minSupport = \request('min_support');

        $productIds = [];
        foreach ($satuSetItem as $item) {
            $productIds[] = $item['product_id'];
        }

        $product4Sets = [];
        $combination4Sets = [];

        foreach (range(1, 12) as $month) {
            $combinations = [];

            $productCount = count($productIds);

            for ($i = 0; $i < $productCount - 3; $i++) {
                for ($j = $i + 1; $j < $productCount - 2; $j++) {
                    for ($k = $j + 1; $k < $productCount - 1; $k++) {
                        for ($l = $k + 1; $l < $productCount; $l++) {
                            $productId1 = $productIds[$i];
                            $productId2 = $productIds[$j];
                            $productId3 = $productIds[$k];
                            $productId4 = $productIds[$l];

                            $product1 = Product::find($productId1);
                            $product2 = Product::find($productId2);
                            $product3 = Product::find($productId3);
                            $product4 = Product::find($productId4);

                            $combinations[] = [
                                'product_id_1' => $productId1,
                                'product_name_1' => $product1->nama,
                                'product_id_2' => $productId2,
                                'product_name_2' => $product2->nama,
                                'product_id_3' => $productId3,
                                'product_name_3' => $product3->nama,
                                'product_id_4' => $productId4,
                                'product_name_4' => $product4->nama,
                            ];
                        }
                    }
                }
            }

            $combination4Sets = array_merge($combination4Sets, $combinations);

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

                $transaksiItem4 = ProsesApriori::join('products','proses_aprioris.product_id', 'products.id')
                    ->join('detail_orders','detail_orders.produk_id', '=', 'products.id')
                    ->join('orders','orders.id', '=', 'detail_orders.id')
                    ->whereYear('proses_aprioris.date', $date)
                    ->whereMonth('proses_aprioris.date', $month)
                    ->where('products.id', $combination['product_id_4'])
                    ->first();

                $results[] = $transaksiItem1 && $transaksiItem2 && $transaksiItem3 && $transaksiItem4 ? 'Y' : 'N';
            }

            $product4Sets[$month] = $results;
        }

        $uniqueCombinations = array_unique($combination4Sets, SORT_REGULAR);

        // Menghitung jumlah "Y" per kombinasi produk dengan indeks yang sama selama 12 bulan
        $totalYesPerIndex = array_fill(0, count($combinations), 0);

        foreach ($product4Sets as $monthResults) {
            foreach ($monthResults as $index => $result) {
                if ($result === 'Y') {
                    $totalYesPerIndex[$index]++;
                }
            }
        }

        $persentase4SetItems = [];
        foreach ($totalYesPerIndex as $persentaseTotalYes){
            $totalStatus = (int) $persentaseTotalYes;
            $persentase4SetItems[] = ($totalStatus / 12) * 100;
        }

        $filteredNameCombinations = array_filter($uniqueCombinations, function($combination, $index) use ($persentase4SetItems, $minSupport) {
            return $persentase4SetItems[$index] >= $minSupport;
        }, ARRAY_FILTER_USE_BOTH);

        $filteredNames = array_map(function($combination) {
            return [
                'product_name_1' => $combination['product_name_1'] . ' => ',
                'product_name_2' => $combination['product_name_2'] . ' => ',
                'product_name_3' => $combination['product_name_3'] . ' => ',
                'product_name_4' => $combination['product_name_4'],
            ];
        }, $filteredNameCombinations);

        /*simpan ke database untuk kategori 4 itemset*/
        foreach ($filteredNameCombinations as $key => $filteredNameCombination){
            $mergedName = [];
            foreach ($filteredNames as $index => $namesArray) {
                $mergedName[$index] = Str::slug($namesArray['product_name_1'] . $namesArray['product_name_2'] . $namesArray['product_name_3'] . $namesArray['product_name_4']);
            }

            $productItemSet = ProductItemSet::whereYear('tahun', $date)
                ->where('kode_item_set', $mergedName[$key])
                ->firstOrNew();
            $productItemSet->kode_item_set = $mergedName[$key];
            $productItemSet->total_transaksi = $totalYesPerIndex[$key];
            $productItemSet->persentase = $persentase4SetItems[$key];
            $productItemSet->kategori = '4_itemset';
            $productItemSet->tahun = $date;
            $productItemSet->save();
        }
        /*===========*/

        return compact('filteredNames', 'totalYesPerIndex', 'persentase4SetItems', 'filteredNameCombinations');
    }

    public function confidence($date, $minConfidence, $satuSetItem)
    {
        /*hasil 2 item set*/
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

        /*hasil 4 set item*/
        $proses4SetItems = $this->proses4SetItem($satuSetItem);
        $filtered4NameCombinations = $proses4SetItems['filteredNameCombinations'];
        $filtered4Names = $proses4SetItems['filteredNames'];
        $total4YesPerIndex = $proses4SetItems['totalYesPerIndex'];
        $persentase4SetItems = $proses4SetItems['persentase4SetItems'];

        /*confidence*/
        $confidenceItemSets = [];
        $nameProductConfidence = [];
        $persentaseMinSupportConfidence = [];
        $tableConfidenceItemSets = [];

        if (count($filtered4NameCombinations) > 0){
            foreach ($filtered4NameCombinations as $key => $value){
                $totalYes = $total4YesPerIndex[$key];

                /*product1 1 => 2 => 3*/
                $productName1 = Str::slug($value['product_name_1'] . '-' . $value['product_name_2'] . '-' . $value['product_name_3']);
                $product1 = ProductItemSet::kodeItemSet($productName1)
                    ->filterKategori('3_itemset')
                    ->where('tahun', $date)
                    ->first();
                $confidenceItemSets[$key][$product1->kode_item_set] = ($totalYes / $product1->total_transaksi) * 100;

                /*product2 1 => 2 => 4*/
                $productName2 = Str::slug($value['product_name_1'] . '-' . $value['product_name_2'] . '-' . $value['product_name_4']);
                $product2 = ProductItemSet::kodeItemSet($productName2)
                    ->filterKategori('3_itemset')
                    ->where('tahun', $date)
                    ->first();
                $confidenceItemSets[$key][$product2->kode_item_set] = ($totalYes / $product2->total_transaksi) * 100;

                /*product3 2 => 3 => 4*/
                $productName3 = Str::slug($value['product_name_2'] . '-' . $value['product_name_3'] . '-' . $value['product_name_4']);
                $product3 = ProductItemSet::kodeItemSet($productName3)
                    ->filterKategori('3_itemset')
                    ->where('tahun', $date)
                    ->first();
                $confidenceItemSets[$key][$product3->kode_item_set] = ($totalYes / $product3->total_transaksi) * 100;

                /*product3 1 => 3 => 4*/
                $productName4 = Str::slug($value['product_name_1'] . '-' . $value['product_name_3'] . '-' . $value['product_name_4']);
                $product4 = ProductItemSet::kodeItemSet($productName4)
                    ->filterKategori('3_itemset')
                    ->where('tahun', $date)
                    ->first();
                $confidenceItemSets[$key][$product4->kode_item_set] = ($totalYes / $product4->total_transaksi) * 100;

                $nameProductConfidence[$key] = $filtered4Names[$key];
                $persentaseMinSupportConfidence[$key] = $persentase4SetItems[$key];
            }

            foreach ($confidenceItemSets as $key => $values) {
                $productNames = $nameProductConfidence[$key];
                $persentaseHasilSupport = $persentaseMinSupportConfidence[$key];

                // Check if both arrays have data for the given key
                if (count($values) > 0 && count($productNames) > 0) {
                    // Assuming there are two items in each array for a key
                    $keys = array_keys($values);
                    $product1 = $keys[0];
                    $product2 = $keys[1];
                    $product3 = $keys[2];
                    $product4 = $keys[3];

                    $confidence1 = $values[$product1];
                    $confidence2 = $values[$product2];
                    $confidence3 = $values[$product3];
                    $confidence4 = $values[$product4];

                    // Removing "=>" character from the strings
                    $productNames['product_name_1'] = str_replace(" =>", "", $productNames['product_name_1']);
                    $productNames['product_name_2'] = str_replace(" =>", "", $productNames['product_name_2']);
                    $productNames['product_name_3'] = str_replace(" =>", "", $productNames['product_name_3']);
                    $productNames['product_name_4'] = str_replace(" =>", "", $productNames['product_name_4']);

                    if ($confidence1 >= $minConfidence) {
                        $tableConfidenceItemSets[] = [
                            'nama_product' => $productNames['product_name_1'] . ", " . $productNames['product_name_2'] . ", " . $productNames['product_name_3'] . " => " . $productNames['product_name_4'],
                            'persentase_hasil_support' => $persentaseHasilSupport,
                            'persentase_hasil_confidence' => $confidence1
                        ];
                    }

                    if ($confidence2 >= $minConfidence) {
                        $tableConfidenceItemSets[] = [
                            'nama_product' => $productNames['product_name_1'] . ", " . $productNames['product_name_2'] . ", " . $productNames['product_name_4'] . " => " . $productNames['product_name_3'],
                            'persentase_hasil_support' => $persentaseHasilSupport,
                            'persentase_hasil_confidence' => $confidence2
                        ];
                    }

                    if ($confidence3 >= $minConfidence) {
                        $tableConfidenceItemSets[] = [
                            'nama_product' => $productNames['product_name_2'] . ", " . $productNames['product_name_3'] . ", " . $productNames['product_name_4'] . " => " . $productNames['product_name_1'],
                            'persentase_hasil_support' => $persentaseHasilSupport,
                            'persentase_hasil_confidence' => $confidence3
                        ];
                    }

                    if ($confidence4 >= $minConfidence) {
                        $tableConfidenceItemSets[] = [
                            'nama_product' => $productNames['product_name_1'] . ", " . $productNames['product_name_3'] . ", " . $productNames['product_name_4'] . " => " . $productNames['product_name_2'],
                            'persentase_hasil_support' => $persentaseHasilSupport,
                            'persentase_hasil_confidence' => $confidence4
                        ];
                    }
                }
            }
        }elseif (count($filtered3NameCombinations) > 0){
            foreach ($filtered3NameCombinations as $key => $value){
                $totalYes = $total3YesPerIndex[$key];

                // product1 1 => 2
                $productName1 = Str::slug($value['product_name_1'] . '-' . $value['product_name_2']);
                $product1 = ProductItemSet::kodeItemSet($productName1)
                    ->filterKategori('2_itemset')
                    ->where('tahun', $date)
                    ->first();
                $confidenceItemSets[$key][$product1->kode_item_set] = ($totalYes / $product1->total_transaksi) * 100;

                // product2 1 => 3
                $productName2 = Str::slug($value['product_name_1'] . '-' . $value['product_name_3']);
                $product2 = ProductItemSet::kodeItemSet($productName2)
                    ->filterKategori('2_itemset')
                    ->where('tahun', $date)
                    ->first();
                $confidenceItemSets[$key][$product2->kode_item_set] = ($totalYes / $product2->total_transaksi) * 100;

                // product3 2 => 3
                $productName3 = Str::slug($value['product_name_2'] . '-' . $value['product_name_3']);
                $product3 = ProductItemSet::kodeItemSet($productName3)
                    ->filterKategori('2_itemset')
                    ->where('tahun', $date)
                    ->first();
                $confidenceItemSets[$key][$product3->kode_item_set] = ($totalYes / $product3->total_transaksi) * 100;

                $nameProductConfidence[$key] = $filtered3Names[$key];
                $persentaseMinSupportConfidence[$key] = $persentase3SetItems[$key];
            }

            foreach ($confidenceItemSets as $key => $values) {
                $productNames = $nameProductConfidence[$key];
                $persentaseHasilSupport = $persentaseMinSupportConfidence[$key];

                // Check if both arrays have data for the given key
                if (count($values) > 0 && count($productNames) > 0) {
                    // Assuming there are two items in each array for a key
                    $keys = array_keys($values);
                    $product1 = $keys[0];
                    $product2 = $keys[1];
                    $product3 = $keys[2];

                    $confidence1 = $values[$product1];
                    $confidence2 = $values[$product2];
                    $confidence3 = $values[$product3];

                    // Removing "=>" character from the strings
                    $productNames['product_name_1'] = str_replace(" =>", "", $productNames['product_name_1']);
                    $productNames['product_name_2'] = str_replace(" =>", "", $productNames['product_name_2']);
                    $productNames['product_name_3'] = str_replace(" =>", "", $productNames['product_name_3']);

                    if ($confidence1 >= $minConfidence) {
                        $tableConfidenceItemSets[] = [
                            'nama_product' => $productNames['product_name_1'] . ", " . $productNames['product_name_2'] . " => " . $productNames['product_name_3'],
                            'persentase_hasil_support' => $persentaseHasilSupport,
                            'persentase_hasil_confidence' => $confidence1
                        ];
                    }

                    if ($confidence2 >= $minConfidence) {
                        $tableConfidenceItemSets[] = [
                            'nama_product' => $productNames['product_name_1'] . ", " . $productNames['product_name_3'] . " => " . $productNames['product_name_2'],
                            'persentase_hasil_support' => $persentaseHasilSupport,
                            'persentase_hasil_confidence' => $confidence2
                        ];
                    }

                    if ($confidence3 >= $minConfidence) {
                        $tableConfidenceItemSets[] = [
                            'nama_product' => $productNames['product_name_2'] . ", " . $productNames['product_name_3'] . " => " . $productNames['product_name_1'],
                            'persentase_hasil_support' => $persentaseHasilSupport,
                            'persentase_hasil_confidence' => $confidence3
                        ];
                    }
                }
            }
        }elseif (count($filtered2NameCombinations) > 0){
            foreach ($filtered2NameCombinations as $key => $value) {
                $totalYes = $total2YesPerIndex[$key];
                // item set ke 1
                $product1 = Product::where('id', $value['product_id_1'])
                    ->pluck('nama');
                $codeProduct1 = Str::slug($product1);
                $productItemSet1 = ProductItemSet::kodeItemSet($codeProduct1)
                    ->where('tahun', $date)
                    ->first();
                $confidenceItemSets[$key][$productItemSet1->kode_item_set] = ($totalYes / $productItemSet1->total_transaksi) * 100;

                // item set ke 2
                $product2 = Product::where('id', $value['product_id_2'])
                    ->pluck('nama');
                $codeProduct2 = Str::slug($product2);
                $productItemSet2 = ProductItemSet::kodeItemSet($codeProduct2)
                    ->where('tahun', $date)
                    ->first();
                $confidenceItemSets[$key][$productItemSet2->kode_item_set] = ($totalYes / $productItemSet2->total_transaksi) * 100;

                $nameProductConfidence[$key] = $filtered2Names[$key];
                $persentaseMinSupportConfidence[$key] = $persentase2SetItems[$key];
            }

            foreach ($confidenceItemSets as $key => $values) {
                $productNames = $nameProductConfidence[$key];
                $persentaseHasilSupport = $persentaseMinSupportConfidence[$key];

                // Check if both arrays have data for the given key
                if (count($values) > 0 && count($productNames) > 0) {
                    // Assuming there are two items in each array for a key
                    $keys = array_keys($values);
                    $product1 = $keys[0];
                    $product2 = $keys[1];

                    $confidence1 = $values[$product1];
                    $confidence2 = $values[$product2];

                    // Removing "=>" character from the strings
                    $productNames['product_name_1'] = str_replace(" =>", "", $productNames['product_name_1']);
                    $productNames['product_name_2'] = str_replace(" =>", "", $productNames['product_name_2']);

                    if ($confidence1 >= $minConfidence) {
                        $tableConfidenceItemSets[] = [
                            'nama_product' => $productNames['product_name_1'] . " => " . $productNames['product_name_2'],
                            'persentase_hasil_support' => $persentaseHasilSupport,
                            'persentase_hasil_confidence' => $confidence1
                        ];
                    }

                    if ($confidence2 >= $minConfidence) {
                        $tableConfidenceItemSets[] = [
                            'nama_product' => $productNames['product_name_2'] . " => " . $productNames['product_name_1'],
                            'persentase_hasil_support' => $persentaseHasilSupport,
                            'persentase_hasil_confidence' => $confidence2
                        ];
                    }
                }
            }
        }

        if (count($tableConfidenceItemSets) > 0){
            $lastBatchNumber = HasilApriori::max('no_urut');
            $lastSequenceNumber = $lastBatchNumber ?: 0;

            foreach ($tableConfidenceItemSets as $tableConfidenceItemSet){
                $hasilApriori = New HasilApriori();
                $hasilApriori->no_urut = $lastSequenceNumber + 1;
                $hasilApriori->kode_pengujian = 'PNG' . str_pad($hasilApriori->no_urut, 5, '0', STR_PAD_LEFT);
                $hasilApriori->penguji = auth()->user()->id;
                $hasilApriori->nama_produk = $tableConfidenceItemSet['nama_product'];
                $hasilApriori->persentase_hasil_support = $tableConfidenceItemSet['persentase_hasil_support'];
                $hasilApriori->persentase_hasil_confidence = $tableConfidenceItemSet['persentase_hasil_confidence'];
                $hasilApriori->tanggal = date('Y-m-d');
                $hasilApriori->save();
            }
        }

        return compact('tableConfidenceItemSets');
    }
}
