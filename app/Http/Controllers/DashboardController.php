<?php

namespace App\Http\Controllers;

use App\Models\HasilApriori;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard', ['only' => ['index']]);
    }

    public function index()
    {
        $title = 'Dashboard';
        $orders = Order::get();
        $products = Product::get();

        $noUrut = HasilApriori::orderByDesc('no_urut')
            ->select('no_urut')
            ->first();
        
        $hasilApriori = [];
        
        if($noUrut){
        	$hasilApriori = HasilApriori::noUrut($noUrut->no_urut)
            ->select([
                'nama_produk',
                'persentase_hasil_support',
                'persentase_hasil_confidence'
            ])
            ->get()
            ->toArray();
        }

        $totalPenjualan = Order::statusOrder('Dikirim')
            ->sum('total_harga_jual');

        return view('dashboard.index', compact('title', 'products', 'orders','hasilApriori','totalPenjualan'));
    }
}
