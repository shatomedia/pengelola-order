<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

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
        $request->validate([
            'nama' => 'required',
            'kategori_id' => 'required',
            'stok' => 'required',
            'satuan' => 'required',
        ]);
        Order::create([
            'status' => $request->status,
            'nama_pembeli' => $request->nama_pembeli,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'produk_id' => $request->produk_id,
            'order_via' => $request->order_via,
            'tgl_order' => $request->tgl_order,
            'tgl_kirim' => $request->tgl_kirim,
            'title' => $request->title,
            'background' => $request->background,
            'request' => $request->request,
            'keterangan' => $request->keterangan
        ]);

        return redirect('/order')->with('success','Data Penjualan berhasil ditambahkan.');
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
