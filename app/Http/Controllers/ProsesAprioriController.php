<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProsesAprioriController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Proses Apriori';
        $date = $request->input('date');
        $minSupport = $request->input('min_support');
        $minConfidence = $request->input('min_confidence');

        if ($date) {
            [$startDate, $endDate] = explode(' - ', $date);
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
            // $products = Product::with([
            //     'orders' => function($query) use ($startDate, $endDate){
            //         $query->whereBetween('tgl_order', [$startDate, $endDate]);
            //     }
            // ])
            //     ->withCount('orders')
            //     ->whereHas('orders', function($query) use ($startDate, $endDate){
            //         $query->whereBetween('tgl_order', [$startDate, $endDate]);
            //     })
            //     ->get();



            $orders = Order::with('product')
                ->whereBetween('tgl_order', [$startDate, $endDate]);

            // $this->prosesApriori($orders, $request);

            $orders = $orders->paginate(10);
        }else{
            $orders = null;
        }
        
        return view('apriories.index', compact(['title','orders']));
    }

    public function prosesApriori($orders, $request) {
        $orders = $orders->select('produk_id')
            ->groupBy('produk_id')
            ->get();
            
        foreach ($orders as $order) {
            $produk = Product::withCount('orders')
                ->find($order->produk_id);
        }
    }

    public function _prosesApriori()
    {
        // Langkah 1: Mengambil Data Transaksi dari Database
        $transactions = DB::table('transactions')
                        ->select('user_id', 'item_name')
                        ->get()
                        ->toArray();

        $minSupport = 2; // Atur support minimum sesuai kebutuhan
        $minConfidence = 0.5; // Atur confidence minimum sesuai kebutuhan

        // Langkah 2: Implementasi Algoritma Apriori
        $candidateItemsets = $this->generateCandidateItemsets($transactions, 2);

        // Langkah 3: Menghitung Support
        $supportCounts = $this->calculateSupport($transactions, $candidateItemsets);

        // Langkah 4: Pruning
        $prunedItemsets = $this->pruneItemsets($supportCounts, $minSupport);

        // Langkah 5: Menghitung Confidence dan Memfilter Aturan Asosiasi
        $associationRules = $this->generateAssociationRules($prunedItemsets, $minConfidence);

        // Hasil akhir
        dd($associationRules);
    }
    
    // Implementasi Algoritma Apriori
    private function generateCandidateItemsets($transactions, $k) {
        $numTransactions = count($transactions);
            $candidateItemsets = [];

            for ($i = 0; $i < $numTransactions; $i++) {
                for ($j = $i + 1; $j < $numTransactions; $j++) {
                    $combinedItemset = array_merge($transactions[$i], $transactions[$j]);
                    $uniqueItems = array_unique($combinedItemset);

                    if (count($uniqueItems) == $k) {
                        sort($uniqueItems);
                        $candidateItemsets[] = $uniqueItems;
                    }
                }
            }

            return $candidateItemsets;
    }

    // Menghitung Support
    private function calculateSupport($transactions, $candidateItemsets) {
        $supportCounts = [];

            foreach ($candidateItemsets as $itemset) {
                $itemsetString = implode(',', $itemset);
                $supportCounts[$itemsetString] = 0;

                foreach ($transactions as $transaction) {
                    if (count(array_intersect($itemset, $transaction)) == count($itemset)) {
                        $supportCounts[$itemsetString]++;
                    }
                }
            }

            return $supportCounts;
    }

    // Pruning
    private function pruneItemsets($supportCounts, $minSupport) {
        $prunedItemsets = [];

        foreach ($supportCounts as $itemsetString => $supportCount) {
            if ($supportCount >= $minSupport) {
                $prunedItemsets[$itemsetString] = $supportCount;
            }
        }

        return $prunedItemsets;
    }

    // Menghitung Confidence dan Memfilter Aturan Asosiasi
    private function generateAssociationRules($prunedItemsets, $minConfidence) {
        $associationRules = [];
    
        foreach ($prunedItemsets as $itemset => $support) {
            $items = explode(',', $itemset);
    
            if (count($items) > 1) {
                $subsets = $this->getSubsets($items);
    
                foreach ($subsets as $subset) {
                    $itemsetX = $subset;
                    $itemsetY = array_diff($items, $subset);
                    $supportX = $prunedItemsets[implode(',', $itemsetX)];
                    $supportXY = $support;
    
                    $confidence = $this->calculateConfidence($supportX, $supportXY);
    
                    $associationRules[] = [
                        'itemsetX' => $itemsetX,
                        'itemsetY' => $itemsetY,
                        'confidence' => $confidence
                    ];
                }
            }
        }
    
        // Memfilter aturan asosiasi berdasarkan confidence
        $filteredRules = [];
    
        foreach ($associationRules as $rule) {
            if ($rule['confidence'] >= $minConfidence) {
                $filteredRules[] = $rule;
            }
        }
    
        return $filteredRules;
    }

    private function getSubsets($items) {
        $subsets = [[]];
        $numItems = count($items);
    
        for ($i = 0; $i < $numItems; $i++) {
            $newSubsets = $subsets;
    
            foreach ($newSubsets as &$subset) {
                array_push($subset, $items[$i]);
            }
    
            $subsets = array_merge($subsets, $newSubsets);
        }
    
        array_shift($subsets);
    
        return $subsets;
    }
    
    private function calculateConfidence($supportX, $supportXY) {
        return $supportXY / $supportX;
    }
    
    
}