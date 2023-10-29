<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HasilAprioriController extends Controller
{
    public function index()
    {
        $title = "Hasil Apriori";
        return view('hasilApriori.index', compact('title'));
    }
}
