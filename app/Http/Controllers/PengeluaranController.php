<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePengeluaranRequest;
use App\Http\Requests\UpdatePengeluaranRequest;
use App\Models\Pengeluaran;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\Log;

class PengeluaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:pengeluaran', ['only' => ['index']]);
        $this->middleware('permission:pengeluaran-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:pengeluaran-edit', ['only' => ['edit', 'update', 'confirm']]);
        $this->middleware('permission:pengeluaran-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Pengeluaran';
        $pengeluarans = Pengeluaran::with('kategori')
            ->orderByDesc('tanggal')
            ->paginate(10);

        return view('pengeluaran.index', compact('title', 'pengeluarans'));
    }

    public function create()
    {
        $title = 'Tambah Pengeluaran';
        $kategoris = TransactionCategory::jenis('pengeluaran')->get();

        return view('pengeluaran.create', compact('title', 'kategoris'));
    }

    public function store(CreatePengeluaranRequest $request)
    {
        try {
            Pengeluaran::create($request->validated());

            alert()->success('Success', 'Data Pengeluaran berhasil ditambahkan.');

            return redirect('/pengeluaran');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Oops', 'Data Error');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $title = 'Edit Pengeluaran';
        $pengeluaran = Pengeluaran::findOrFail($id);
        $kategoris = TransactionCategory::jenis('pengeluaran')->get();

        return view('pengeluaran.edit', compact('title', 'pengeluaran', 'kategoris'));
    }

    public function update(UpdatePengeluaranRequest $request, $id)
    {
        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            $pengeluaran->update($request->validated());

            alert()->success('Success', 'Data Pengeluaran berhasil diupdate.');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Oops', 'Data Error');
        }

        return redirect()->back();
    }

    public function confirm($id)
    {
        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            $pengeluaran->status = 'terkonfirmasi';
            $pengeluaran->save();

            alert()->success('Success', 'Pengeluaran berhasil dikonfirmasi.');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Oops', 'Data Error');
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            $pengeluaran->delete();

            alert()->success('Success', 'Data Pengeluaran berhasil dihapus.');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Oops', 'Data Error');
        }

        return redirect()->back();
    }
}
