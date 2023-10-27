<?php

namespace App\Http\Controllers;

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
        $orders = Order::paginate(10);
        return view('orders.index', compact(['title', 'orders']));
    }

    public function create()
    {
        $title = 'Tambah Penjualan';
        return view('orders.create', compact(['title']));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'status' => ['required'],
                'nama_pembeli' => ['required'],
                'alamat' => ['required'],
                'no_hp' => ['required'],
                'produk_id' => ['required'],
                'qty' => ['required'],
                'order_via' => ['required'],
                'tgl_order' => ['required', 'date', 'before_or_equal:tgl_kirim'],
                'tgl_kirim' => ['required', 'date', 'after_or_equal:tgl_order'],
                'title' => ['required'],
                'background' => ['required'],
                'request' => ['required'],
                'keterangan' => ['required']
            ]);

            $productId = $request->input('produk_id');
            $product = Product::find($productId);

            if ($productId > $product->stok){
                alert()->warning('Stok tidak mencukupi');
                return redirect()->back();
            }

            DB::transaction(function () use ($productId, $product, $request){
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
                $detailOrder->qty = $request->input('qty');

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
        $orders = Order::find($id);
        return view('orders.edit', compact(['title', 'orders']));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'kategori_id' => 'required',
            'stok' => 'required',
            'satuan' => 'required',
        ]);
        $products = Order::find($id);
        $products->update([
            "nama" => $request->nama,
            "kategori_id" => $request->kategori,
            "harga" => $request->harga,
            "deskripsi" => $request->deskripsi,
            "stok" => $request->stok,
            "satuan" => $request->satuan,
        ]);

        return redirect('/order')->with('success','Data Penjualan berhasil diedit.');
    }

    public function destroy($id)
    {
        $products = Order::find($id);
        $products->delete();

        return redirect('/order')->with('success','Data Penjualan berhasil dihapus.');
    }
}
