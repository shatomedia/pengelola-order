<?php

namespace App\Http\Controllers;

use App\Exports\TemplateExportOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class TemplateOrderController extends Controller
{
    public function export()
    {
        try {
            return Excel::download(new TemplateExportOrder(), 'template-penjualan.xlsx');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->with('error','Data error.');
        }
    }
}
