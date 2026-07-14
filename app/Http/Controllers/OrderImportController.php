<?php

namespace App\Http\Controllers;

use App\Imports\OrderImport;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel as ExcelType;

class OrderImportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'template' => 'required|mimes:xlsx,xls|file|max:200'
            ]);

            $file = $request->file('template')->getRealPath();

            $import = new OrderImport();

            Excel::import($import, $file, null, ExcelType::XLSX);

            \alert()->success('Success', 'Import Penjualan berhasil tersimpan');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            \alert()->error('Oops', 'Data Penjualan gagal diimport');
        }

        return redirect()->back();
    }
}
