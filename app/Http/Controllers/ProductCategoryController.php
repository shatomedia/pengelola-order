<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:product-category', ['only' => ['index']]);
        $this->middleware('permission:product-category-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product-category-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:product-category-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Kategori Produk';
        $productcategories = ProductCategory::all();
        return view('product_categories.index', compact(['title', 'productcategories']));
    }

    public function create()
    {
        $title = 'Tambah Kategori Produk';
        return view('product_categories.create', compact(['title']));
    }

    public  function store(Request $request)
    {
        
        $request->validate([
            'nama_kategori' => 'required'
        ]);
        ProductCategory::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect('/product-category')->with('success','Data kategori produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $title = 'Edit Kategori Produk';
        $productcategories = ProductCategory::find($id);
        return view('product_categories.edit', compact(['title', 'productcategories']));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required'
        ]);
        $productcategories = ProductCategory::find($id);
        $productcategories->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect('/product-category')->with('success','Data kategori produk berhasil diedit.');
    }

    public function destroy($id)
    {
        $productcategories = ProductCategory::find($id);
        $productcategories->delete();

        return redirect('/product-category')->with('success','Data kategori produk berhasil dihapus.');
    }
}
