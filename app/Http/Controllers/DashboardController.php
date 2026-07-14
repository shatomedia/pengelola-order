<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\HasilApriori;
use App\Models\Order;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\PengeluaranBerulang;
use App\Models\Product;
use App\Models\ProductCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $category = ProductCategory::get();
        
        $today = Carbon::now()->format('Y-m-d');
        $ordersToday = Order::whereDate('created_at', $today)
            ->get();

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

        $bulanIni = Carbon::now();
        $totalOrderBulanIni = Order::whereYear('tgl_order', $bulanIni->year)
            ->whereMonth('tgl_order', $bulanIni->month)
            ->count();
        $pendapatanBulanIni = Order::whereYear('tgl_order', $bulanIni->year)
            ->whereMonth('tgl_order', $bulanIni->month)
            ->sum('total_harga_jual');
        $piutang = (int) Order::where('payment_status', '!=', 'Lunas')
            ->selectRaw('SUM(GREATEST(total_harga_jual - jumlah_dibayar, 0)) as total')
            ->value('total');
        $stokMenipisCount = Product::where('stok', '<=', 5)->count();

        $trenLabels = [];
        $trenData = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $trenLabels[] = $bulan->isoFormat('MMM YYYY');
            $trenData[] = (int) Order::whereYear('tgl_order', $bulan->year)
                ->whereMonth('tgl_order', $bulan->month)
                ->sum('total_harga_jual');
        }

        $paymentStatusCounts = Order::select('payment_status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('payment_status')
            ->pluck('total', 'payment_status');

        $produkTerlaris = DetailOrder::join('products', 'products.id', '=', 'detail_orders.produk_id')
            ->select('products.nama')
            ->selectRaw('SUM(detail_orders.qty) as total_qty')
            ->groupBy('products.id', 'products.nama')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->total_qty = (int) $item->total_qty;
                return $item;
            });

        $produkStokMenipis = Product::where('stok', '<=', 5)
            ->orderBy('stok')
            ->take(5)
            ->get(['nama', 'stok']);

        $totalPemasukanNonOrderBulanIni = Pemasukan::whereYear('tanggal', $bulanIni->year)
            ->whereMonth('tanggal', $bulanIni->month)
            ->sum('jumlah');
        $totalPemasukanBulanIni = $pendapatanBulanIni + $totalPemasukanNonOrderBulanIni;
        $totalPengeluaranBulanIni = (int) Pengeluaran::terkonfirmasi()
            ->whereYear('tanggal', $bulanIni->year)
            ->whereMonth('tanggal', $bulanIni->month)
            ->sum('jumlah');
        $labaBersihBulanIni = $totalPemasukanBulanIni - $totalPengeluaranBulanIni;

        $keuanganTrenLabels = [];
        $keuanganTrenPemasukan = [];
        $keuanganTrenPengeluaran = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $keuanganTrenLabels[] = $bulan->isoFormat('MMM YYYY');

            $penjualanBulan = (int) Order::whereYear('tgl_order', $bulan->year)
                ->whereMonth('tgl_order', $bulan->month)
                ->sum('total_harga_jual');
            $pemasukanLainBulan = (int) Pemasukan::whereYear('tanggal', $bulan->year)
                ->whereMonth('tanggal', $bulan->month)
                ->sum('jumlah');
            $keuanganTrenPemasukan[] = $penjualanBulan + $pemasukanLainBulan;

            $keuanganTrenPengeluaran[] = (int) Pengeluaran::terkonfirmasi()
                ->whereYear('tanggal', $bulan->year)
                ->whereMonth('tanggal', $bulan->month)
                ->sum('jumlah');
        }

        $pengeluaranBerulangPendingCount = PengeluaranBerulang::aktif()
            ->get()
            ->reject(function ($template) use ($bulanIni) {
                return Pengeluaran::where('pengeluaran_berulang_id', $template->id)
                    ->whereYear('tanggal', $bulanIni->year)
                    ->whereMonth('tanggal', $bulanIni->month)
                    ->exists();
            })
            ->count();

        $pengeluaranBerulangOverBudgetCount = PengeluaranBerulang::aktif()
            ->get()
            ->filter(function ($template) use ($bulanIni) {
                $realisasi = (int) Pengeluaran::terkonfirmasi()
                    ->where('pengeluaran_berulang_id', $template->id)
                    ->whereYear('tanggal', $bulanIni->year)
                    ->whereMonth('tanggal', $bulanIni->month)
                    ->sum('jumlah');

                return $realisasi > $template->jumlah_estimasi;
            })
            ->count();

        $orderPiutangList = Order::where('payment_status', '!=', 'Lunas')
            ->orderBy('tgl_order')
            ->get();

        return view('dashboard.index', compact(
            'title', 'products', 'category', 'orders', 'hasilApriori', 'totalPenjualan', 'ordersToday',
            'totalOrderBulanIni', 'pendapatanBulanIni', 'piutang', 'stokMenipisCount',
            'trenLabels', 'trenData', 'paymentStatusCounts', 'produkTerlaris', 'produkStokMenipis',
            'totalPemasukanBulanIni', 'totalPengeluaranBulanIni', 'labaBersihBulanIni',
            'keuanganTrenLabels', 'keuanganTrenPemasukan', 'keuanganTrenPengeluaran',
            'pengeluaranBerulangPendingCount', 'pengeluaranBerulangOverBudgetCount', 'orderPiutangList'
        ));
    }
}
