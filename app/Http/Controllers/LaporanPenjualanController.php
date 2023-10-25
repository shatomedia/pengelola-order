<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        $title = "Laporan Penjualan";
        return view('laporanPenjualan.index', compact(['title']));
    }
}
