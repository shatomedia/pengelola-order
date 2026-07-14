<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePemasukanRequest;
use App\Http\Requests\UpdatePemasukanRequest;
use App\Models\Pemasukan;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Log;

class PemasukanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:pemasukan', ['only' => ['index']]);
        $this->middleware('permission:pemasukan-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:pemasukan-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:pemasukan-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Pemasukan';
        $pemasukans = Pemasukan::with('kategori', 'order')
            ->orderByDesc('tanggal')
            ->paginate(10);

        return view('pemasukan.index', compact('title', 'pemasukans'));
    }

    public function create()
    {
        $title = 'Tambah Pemasukan';
        $kategoris = TransactionCategory::jenis('pemasukan')->get();

        return view('pemasukan.create', compact('title', 'kategoris'));
    }

    public function store(CreatePemasukanRequest $request)
    {
        try {
            Pemasukan::create($request->validated());

            alert()->success('Success', 'Data Pemasukan berhasil ditambahkan.');

            return redirect('/pemasukan');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Oops', 'Data Error');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $title = 'Edit Pemasukan';
        $pemasukan = Pemasukan::findOrFail($id);
        $kategoris = TransactionCategory::jenis('pemasukan')->get();

        return view('pemasukan.edit', compact('title', 'pemasukan', 'kategoris'));
    }

    public function update(UpdatePemasukanRequest $request, $id)
    {
        try {
            $pemasukan = Pemasukan::findOrFail($id);
            $pemasukan->update($request->validated());

            alert()->success('Success', 'Data Pemasukan berhasil diupdate.');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Oops', 'Data Error');
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $pemasukan = Pemasukan::findOrFail($id);
            $pemasukan->delete();

            alert()->success('Success', 'Data Pemasukan berhasil dihapus.');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Oops', 'Data Error');
        }

        return redirect()->back();
    }
}
