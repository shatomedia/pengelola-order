<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerController extends Controller
{
    const CHURN_DAYS = 90;

    public function __construct()
    {
        $this->middleware('permission:order', ['only' => ['index', 'show', 'togglePromo', 'export']]);
    }

    public function index(Request $request)
    {
        $title = 'Pelanggan';
        $search = $request->input('search');
        $promoStatus = $request->input('promo_status');
        $sort = $request->input('sort', 'total_belanja');

        $customers = Customer::withCount('orders')
            ->withSum('orders', 'total_harga_jual')
            ->withMax('orders', 'tgl_order')
            ->when($search, function ($query) use ($search) {
                $query->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('no_hp', 'LIKE', "%{$search}%");
            })
            ->when($promoStatus === 'sudah', fn ($query) => $query->where('promo_ditawarkan', true))
            ->when($promoStatus === 'belum', fn ($query) => $query->where('promo_ditawarkan', false))
            ->when($sort === 'terlama-order', fn ($query) => $query->orderBy('orders_max_tgl_order'))
            ->when($sort === 'total_belanja', fn ($query) => $query->orderByDesc('orders_sum_total_harga_jual'))
            ->paginate(25)
            ->withQueryString();

        return view('customers.index', compact('title', 'customers', 'search', 'promoStatus', 'sort'));
    }

    public function show($id)
    {
        $title = 'Riwayat Pelanggan';
        $customer = Customer::with(['orders' => function ($query) {
            $query->orderByDesc('tgl_order');
        }])->findOrFail($id);

        return view('customers.show', compact('title', 'customer'));
    }

    public function togglePromo($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->promo_ditawarkan = ! $customer->promo_ditawarkan;
        $customer->promo_ditawarkan_at = $customer->promo_ditawarkan ? now() : null;
        $customer->save();

        return back()->with('success', $customer->promo_ditawarkan
            ? "{$customer->nama} ditandai sudah ditawarkan promo."
            : "{$customer->nama} ditandai belum ditawarkan promo.");
    }

    public function export(Request $request): StreamedResponse
    {
        $search = $request->input('search');
        $promoStatus = $request->input('promo_status');

        $customers = Customer::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('no_hp', 'LIKE', "%{$search}%");
            })
            ->when($promoStatus === 'sudah', fn ($query) => $query->where('promo_ditawarkan', true))
            ->when($promoStatus === 'belum', fn ($query) => $query->where('promo_ditawarkan', false))
            ->orderBy('nama')
            ->get(['nama', 'no_hp', 'alamat', 'promo_ditawarkan', 'promo_ditawarkan_at']);

        $filename = 'kontak-promo-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($customers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nama', 'No HP', 'Alamat', 'Status Promo', 'Tanggal Ditawarkan']);

            foreach ($customers as $customer) {
                fputcsv($handle, [
                    $customer->nama,
                    $customer->no_hp,
                    $customer->alamat,
                    $customer->promo_ditawarkan ? 'Sudah ditawarkan' : 'Belum ditawarkan',
                    optional($customer->promo_ditawarkan_at)->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
