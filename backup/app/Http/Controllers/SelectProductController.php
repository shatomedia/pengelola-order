<?php

namespace App\Http\Controllers;

use App\Models\Product;

class SelectProductController extends Controller
{
    public function index()
    {
        $search = request()->input('q');

        $products = [];
        $products = Product::when($search != null ?? false, fn($query) => $query->where('nama', 'LIKE', "%$search%"))
            ->select([
                'id',
                'nama'
            ])
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}
