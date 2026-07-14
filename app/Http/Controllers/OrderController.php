<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:order', ['only' => ['index', 'invoice', 'invoiceThermal']]);
        $this->middleware('permission:order-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:order-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $title = 'Penjualan';
        $perPage = (int) $request->input('per_page', 25);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 25;

        $orders = Order::withCount('detailOrders')
            ->orderByDesc('tgl_order')
            ->paginate($perPage)
            ->withQueryString();

        return view('orders.index', compact('title', 'orders', 'perPage'));
    }

    public function create()
    {
        $title = 'Tambah Penjualan';
        return view('orders.create', compact('title'));
    }

    public function store(CreateOrderRequest $request)
    {
        try {
            $produkIds = $request->input('produk_id');
            $qtys = $request->input('qty');

            $products = Product::whereIn('id', $produkIds)->get()->keyBy('id');

            foreach ($produkIds as $index => $produkId) {
                $product = $products->get($produkId);
                if (!$product || $qtys[$index] > $product->stok) {
                    alert()->warning('Oops..', 'Stok ' . optional($product)->nama . ' tidak mencukupi');
                    return redirect()->back()->withInput();
                }
            }

            DB::transaction(function () use ($produkIds, $qtys, $products, $request){
                $customer = Customer::updateOrCreate(
                    ['no_hp' => $request->input('no_hp')],
                    ['nama' => $request->input('nama_pembeli'), 'alamat' => $request->input('alamat')]
                );

                $order = New Order();
                $order->customer_id = $customer->id;
                $order->status = $request->input('status');
                $order->payment_status = $request->input('payment_status');
                $order->jumlah_dibayar = $request->input('jumlah_dibayar', 0);
                $order->nama_pembeli = $request->input('nama_pembeli');
                $order->alamat = $request->input('alamat');
                $order->no_hp = $request->input('no_hp');
                $order->order_via = $request->input('order_via');
                $order->tgl_order = $request->input('tgl_order');
                $order->tgl_kirim = $request->input('tgl_kirim');
                $order->title = $request->input('title');
                $order->background = $request->input('background');
                $order->request = $request->input('request');
                $order->keterangan = $request->input('keterangan');
                $order->save();

                $totalQty = 0;
                $totalHargaJual = 0;

                foreach ($produkIds as $index => $produkId) {
                    $qty = (int) $qtys[$index];
                    $product = $products->get($produkId);

                    $detailOrder = New DetailOrder();
                    $detailOrder->order_id = $order->id;
                    $detailOrder->produk_id = $product->id;
                    $detailOrder->qty = $qty;

                    $subTotal = $qty * intval($product->harga);
                    $detailOrder->sub_total = $subTotal;
                    $detailOrder->save();

                    $totalQty += $qty;
                    $totalHargaJual += $subTotal;

                    $product->stok = $product->stok - $qty;
                    $product->save();
                }

                $order->total_qty = $totalQty;
                $order->total_harga_jual = $totalHargaJual;
                $order->save();
            });

            alert()->success('success','Data Penjualan berhasil ditambahkan.');

            return redirect('/order');
        }catch (\Throwable $throwable){
            Log::error($throwable->getMessage());
            alert()->error('Oops', 'Data Error');

            return redirect()->back();
        }
    }

    public function invoice($id)
    {
        $order = Order::with('detailOrders.produk', 'customer')->findOrFail($id);

        return view('orders.invoice', compact('order'));
    }

    public function invoiceThermal($id)
    {
        $order = Order::with('detailOrders.produk', 'customer')->findOrFail($id);

        return view('orders.invoice-thermal', compact('order'));
    }

    public function edit($id)
    {
        $title = 'Edit Penjualan';
        $order = Order::with('detailOrders')
            ->withSum('detailOrders', 'sub_total')
            ->withSum('detailOrders', 'qty')
            ->findOrFail($id);
        $products = Product::with('detailOrder')
            ->whereDoesntHave('detailOrder', function ($query) use ($order){
                $query->where('order_id', $order->id);
            })
            ->get();

        return view('orders.edit', compact('title', 'order','products'));
    }

    public function update(UpdateOrderRequest $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $customer = Customer::updateOrCreate(
                ['no_hp' => $request->input('no_hp')],
                ['nama' => $request->input('nama_pembeli'), 'alamat' => $request->input('alamat')]
            );

            $order->customer_id = $customer->id;
            $order->status = $request->input('status');
            $order->payment_status = $request->input('payment_status');
            $order->jumlah_dibayar = $request->input('jumlah_dibayar');
            $order->nama_pembeli = $request->input('nama_pembeli');
            $order->alamat = $request->input('alamat');
            $order->no_hp = $request->input('no_hp');
            $order->order_via = $request->input('order_via');
            $order->tgl_order = $request->input('tgl_order');
            $order->tgl_kirim = $request->input('tgl_kirim');
            $order->title = $request->input('title');
            $order->background = $request->input('background');
            $order->request = $request->input('request');
            $order->keterangan = $request->input('keterangan');
            $order->save();

            alert()->success('success','Data Penjualan berhasil diupdate.');
        }catch (\Throwable $throwable){
            Log::error($throwable->getMessage());
            alert()->error('Oops', 'Data Error');
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id){
                $order = Order::with('detailOrders')
                    ->findOrFail($id);

                if ($order->detailOrders->count() > 0 && $order->status != 'Dikirim'){
                    foreach ($order->detailOrders as $detailOrder){
                        $product = Product::find($detailOrder->produk_id);

                        $stok = $detailOrder->qty + $product->stok;

                        $product->stok = $stok;
                        $product->save();
                    }
                }

                $order->delete();
            });

            alert()->success('Success', 'Pesanan Berhasil Terhapus');
        }catch (\Throwable $throwable){
            Log::error($throwable->getMessage());
            alert()->error('Oops', 'Data error');
        }

        return redirect()->back();
    }
}
