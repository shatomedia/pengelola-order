<?php

namespace App\Http\Controllers;

use App\Models\HasilApriori;
use Illuminate\Http\Request;

class HasilAprioriController extends Controller
{
    public function index()
    {
        $title = "Hasil Apriori";
        $hasilAprioris = HasilApriori::with('user')
            ->paginate(10);

        return view('hasilApriori.index', compact('title','hasilAprioris'));
    }
}
