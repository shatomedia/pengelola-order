<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:product', ['only' => ['index']]);
        $this->middleware('permission:product-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Produk';
        $products = Product::with('category')->get();
        return view('products.index', compact(['title', 'products']));
    }

    public function create()
    {
        $title = 'Tambah Produk';
        $categories = ProductCategory::get();
        return view('products.create', compact(['title', 'categories']));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'nama' => 'required',
            'kategori_id' => 'required',
            'harga'=> 'required',
            'deskripsi' => 'nullable',
            'stok' => 'required',
            'satuan' => 'required',
        ]);

        try {
            Product::create([
                "kode_produk" => Str::slug($request->input('nama')),
                "nama" => $request->input('nama'),
                "kategori_id" => $request->input('kategori_id'),
                "harga" => $request->input('harga'),
                "deskripsi" => $request->input('deskripsi'),
                "stok" => $request->input('stok'),
                "satuan" => $request->input('satuan'),
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->with('error','Data produk error.');
        }

       

        return redirect('/product')->with('success','Data kategori produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $title = 'Edit Produk';
        $products = Product::find($id);
        return view('products.edit', compact(['title', 'products']));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'kategori_id' => 'required',
            'stok' => 'required',
            'satuan' => 'required',
        ]);
        $products = Product::findOrFail($id);
        try {
            $products->update([
                "kode_produk" => Str::slug($request->input('nama')),
                "nama" => $request->input('nama'),
                "kategori_id" => $request->input('kategori'),
                "harga" => $request->input('harga'),
                "deskripsi" => $request->input('deskripsi'),
                "stok" => $request->input('stok'),
                "satuan" => $request->input('satuan'),
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->with('error','Data produk error.');
        }
        

        return redirect('/product')->with('success','Data produk berhasil diedit.');
    }

    public function destroy($id)
    {
        $products = Product::find($id);
        $products->delete();

        return redirect('/product')->with('success','Data produk berhasil dihapus.');
    }
}
