<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:order', ['only' => ['index', 'show']]);
    }

    public function index(Request $request)
    {
        $title = 'Pelanggan';
        $search = $request->input('search');

        $customers = Customer::withCount('orders')
            ->withSum('orders', 'total_harga_jual')
            ->when($search, function ($query) use ($search) {
                $query->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('no_hp', 'LIKE', "%{$search}%");
            })
            ->orderByDesc('orders_sum_total_harga_jual')
            ->paginate(25)
            ->withQueryString();

        return view('customers.index', compact('title', 'customers', 'search'));
    }

    public function show($id)
    {
        $title = 'Riwayat Pelanggan';
        $customer = Customer::with(['orders' => function ($query) {
            $query->orderByDesc('tgl_order');
        }])->findOrFail($id);

        return view('customers.show', compact('title', 'customer'));
    }
}
