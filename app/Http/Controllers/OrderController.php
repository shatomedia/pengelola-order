<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddOrderProdukRequest;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
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
        $this->middleware('permission:order', ['only' => ['index']]);
        $this->middleware('permission:order-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:order-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Penjualan';
        $orders = Order::withCount('detailOrders')
            ->paginate(10);

        return view('orders.index', compact('title', 'orders'));
    }

    public function create()
    {
        $title = 'Tambah Penjualan';
        return view('orders.create', compact('title'));
    }

    public function store(CreateOrderRequest $request)
    {
        try {
            $productId = $request->input('produk_id');
            $qty = $request->input('qty');
            $product = Product::findOrFail($productId);

            if ($qty > $product->stok){
                alert()->warning('Oops..', 'Stok tidak mencukupi');
                return redirect()->back();
            }

            DB::transaction(function () use ($qty, $product, $request){
                $order = New Order();
                $order->status = $request->input('status');
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

                $detailOrder = New DetailOrder();
                $detailOrder->order_id = $order->id;
                $detailOrder->produk_id = $product->id;
                $detailOrder->qty = $qty;

                $subTotal = $detailOrder->qty * intval($product->harga);

                $detailOrder->sub_total = $subTotal;
                $detailOrder->save();

                /*rekap total qty dan harga jual*/
                $updateOrder = Order::with('detailOrders')
                    ->withSum('detailOrders', 'qty')
                    ->withSum('detailOrders', 'sub_total')
                    ->find($order->id);

                $updateOrder->total_qty = $updateOrder->detail_orders_sum_qty;
                $updateOrder->total_harga_jual = $updateOrder->detail_orders_sum_sub_total;
                $updateOrder->save();

                /*update stok produk*/
                $stok = $product->stok - $updateOrder->total_qty;
                $product->stok = $stok;
                $product->save();
            });

            alert()->success('success','Data Penjualan berhasil ditambahkan.');

            return redirect('/order');
        }catch (\Throwable $throwable){
            Log::error($throwable->getMessage());
            alert()->error('Oops', 'Data Error');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $title = 'Edit Penjualan';
        $order = Order::with('detailOrders')
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
            $order->status = $request->input('status');
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

                if ($order->detail_orders_count > 0 && $order->status != 'Dikirim'){
                    foreach ($order->detailOrders as $detailOrder){
                        $product = Product::find($detailOrder->produk_id);

                        $stok = $detailOrder->sub_total + $product->stok;

                        $product->stok = $stok;
                        $product->save();
                    }
                }

                $order->delete();
            });

            alert()->success('Success', 'Pesanan Berhasil Terhapus');
        }catch (\Throwable $throwable){
            Log::error($throwable->getMessage());
            alert()->erro('Oops', 'Data error');
        }

        return redirect()->back();
    }

    public function newProduct(AddOrderProdukRequest $request, $id)
    {
        try {
            $productId = $request->input('produk_id');
            $qty = $request->input('qty');
            $product = Product::findOrFail($productId);

            if ($qty > $product->stok){
                alert()->warning('Oops..', 'Stok tidak mencukupi');
                return redirect()->back();
            }

            DB::transaction(function () use ($request, $qty, $product, $id){
                $order = Order::withSum('detailOrders', 'sub_total')
                    ->withSum('detailOrders', 'qty')
                    ->findOrFail($id);

                $detailOrder = New DetailOrder();
                $detailOrder->order_id = $order->id;
                $detailOrder->produk_id = $product->id;
                $detailOrder->qty = $qty;

                $subTotal = intval($qty) * intval($product->harga);

                $detailOrder->sub_total = $subTotal;
                $detailOrder->save();

                /*hitung ulang total qty dan total harga jual*/
                $order->total_qty = $order->detail_orders_sum_qty;
                $order->total_harga_jual = $order->detail_orders_sum_sub_total;
                $order->save();

                /*update stok produk*/
                $stok = (int) $product->stok - (int) $order->total_qty;
                dd($qty);
                $product->stok = $stok;
                $product->save();
            });

            alert()->success('Success', 'Produk penjualan berhasil ditambah');
        }catch (\Throwable $throwable){
            Log::error($throwable->getMessage());
            alert()->error('Oops', 'Data Error');
        }

        return redirect()->back();
    }
}
