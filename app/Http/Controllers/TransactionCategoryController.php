<?php

namespace App\Http\Controllers;

use App\Models\TransactionCategory;
use Illuminate\Http\Request;

class TransactionCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:kategori-keuangan', ['only' => ['index']]);
        $this->middleware('permission:kategori-keuangan-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:kategori-keuangan-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:kategori-keuangan-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Kategori Keuangan';
        $transactionCategories = TransactionCategory::orderBy('jenis')->orderBy('nama_kategori')->get();

        return view('kategori-keuangan.index', compact('title', 'transactionCategories'));
    }

    public function create()
    {
        $title = 'Tambah Kategori Keuangan';

        return view('kategori-keuangan.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => ['required'],
            'jenis' => ['required', 'in:pemasukan,pengeluaran'],
            'deskripsi' => ['nullable'],
        ]);

        TransactionCategory::create($request->only('nama_kategori', 'jenis', 'deskripsi'));

        return redirect('/kategori-keuangan')->with('success', 'Kategori keuangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $title = 'Edit Kategori Keuangan';
        $transactionCategory = TransactionCategory::findOrFail($id);

        return view('kategori-keuangan.edit', compact('title', 'transactionCategory'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => ['required'],
            'jenis' => ['required', 'in:pemasukan,pengeluaran'],
            'deskripsi' => ['nullable'],
        ]);

        $transactionCategory = TransactionCategory::findOrFail($id);
        $transactionCategory->update($request->only('nama_kategori', 'jenis', 'deskripsi'));

        return redirect('/kategori-keuangan')->with('success', 'Kategori keuangan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $transactionCategory = TransactionCategory::findOrFail($id);
        $transactionCategory->delete();

        return redirect('/kategori-keuangan')->with('success', 'Kategori keuangan berhasil dihapus.');
    }
}
