<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePengeluaranBerulangRequest;
use App\Http\Requests\UpdatePengeluaranBerulangRequest;
use App\Models\Pengeluaran;
use App\Models\PengeluaranBerulang;
use App\Models\TransactionCategory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PengeluaranBerulangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:pengeluaran-berulang', ['only' => ['index']]);
        $this->middleware('permission:pengeluaran-berulang-create', ['only' => ['create', 'store', 'generate']]);
        $this->middleware('permission:pengeluaran-berulang-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:pengeluaran-berulang-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Pengeluaran Berulang';
        $bulan = Carbon::now()->month;
        $tahun = Carbon::now()->year;

        $templates = PengeluaranBerulang::with('kategori')
            ->orderBy('nama')
            ->get()
            ->map(function ($template) use ($bulan, $tahun) {
                $template->sudah_generate = Pengeluaran::where('pengeluaran_berulang_id', $template->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->exists();

                return $template;
            });

        return view('pengeluaran-berulang.index', compact('title', 'templates'));
    }

    public function create()
    {
        $title = 'Tambah Pengeluaran Berulang';
        $kategoris = TransactionCategory::jenis('pengeluaran')->get();

        return view('pengeluaran-berulang.create', compact('title', 'kategoris'));
    }

    public function store(CreatePengeluaranBerulangRequest $request)
    {
        try {
            $data = $request->validated();
            $data['aktif'] = $request->boolean('aktif', true);

            PengeluaranBerulang::create($data);

            alert()->success('Success', 'Pengeluaran Berulang berhasil ditambahkan.');

            return redirect('/pengeluaran-berulang');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Oops', 'Data Error');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $title = 'Edit Pengeluaran Berulang';
        $pengeluaranBerulang = PengeluaranBerulang::findOrFail($id);
        $kategoris = TransactionCategory::jenis('pengeluaran')->get();

        return view('pengeluaran-berulang.edit', compact('title', 'pengeluaranBerulang', 'kategoris'));
    }

    public function update(UpdatePengeluaranBerulangRequest $request, $id)
    {
        try {
            $pengeluaranBerulang = PengeluaranBerulang::findOrFail($id);
            $data = $request->validated();
            $data['aktif'] = $request->boolean('aktif', true);

            $pengeluaranBerulang->update($data);

            alert()->success('Success', 'Pengeluaran Berulang berhasil diupdate.');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Oops', 'Data Error');
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $pengeluaranBerulang = PengeluaranBerulang::findOrFail($id);
            $pengeluaranBerulang->delete();

            alert()->success('Success', 'Pengeluaran Berulang berhasil dihapus.');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Oops', 'Data Error');
        }

        return redirect()->back();
    }

    public function generate()
    {
        try {
            $bulan = Carbon::now()->month;
            $tahun = Carbon::now()->year;
            $jumlahDibuat = 0;

            $templates = PengeluaranBerulang::aktif()->get();

            foreach ($templates as $template) {
                $sudahAda = Pengeluaran::where('pengeluaran_berulang_id', $template->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->exists();

                if ($sudahAda) {
                    continue;
                }

                $tanggal = Carbon::now()->day(min($template->tanggal_jatuh_tempo, Carbon::now()->daysInMonth));

                Pengeluaran::create([
                    'kategori_id' => $template->kategori_id,
                    'pengeluaran_berulang_id' => $template->id,
                    'jumlah' => $template->jumlah_estimasi,
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'keterangan' => $template->nama,
                    'status' => 'draft',
                ]);

                $jumlahDibuat++;
            }

            alert()->success('Success', $jumlahDibuat . ' tagihan bulan ini berhasil dibuat sebagai draft. Silakan review & konfirmasi di halaman Pengeluaran.');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            alert()->error('Oops', 'Data Error');
        }

        return redirect()->back();
    }
}
