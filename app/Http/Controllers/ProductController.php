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
        return view('products.index', compact('title', 'products'));
    }

    public function create()
    {
        $title = 'Tambah Produk';
        $categories = ProductCategory::get();
        return view('products.create', compact('title', 'categories'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'nama' => 'required',
            'kategori_id' => 'required',
            'harga'=> 'required',
            'harga_modal' => 'nullable',
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
                "harga_modal" => $request->input('harga_modal'),
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
        return view('products.edit', compact('title', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'sometimes|required',
            'stok' => 'sometimes|required',
            'satuan' => 'sometimes|required',
            'harga' => 'sometimes|required',
            'deskripsi' => 'sometimes|required',
        ]);
        $products = Product::findOrFail($id);
        $fieldsToUpdate = ['nama', 'kategori_id', 'stok', 'satuan', 'harga', 'harga_modal', 'deskripsi'];
        $data = [];

        foreach ($fieldsToUpdate as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->input($field);
            }
        }
        try {
            $products->update($data);
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

    public function listProducts(Request $request)
    {
        $request = $request->input('q');

        $products = array();
        $products = Product::when($request, function ($query) use ($request){
                $query->where('nama', 'LIKE', "%$request%");
            })
            ->select([
                'id',
                'nama',
            ])
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}
