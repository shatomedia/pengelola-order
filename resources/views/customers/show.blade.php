@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Riwayat Pembelian: {{ $customer->nama }}</h6>
                    <p class="text-sm text-secondary mb-0">{{ $customer->no_hp }} &mdash; {{ $customer->alamat }}</p>
                </div>
                <div class="card-body px-0 pt-3 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-bordered mb-0 table-mobile-cards">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">No. Faktur</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Pembayaran</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Tgl Order</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total Harga Jual</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customer->orders as $order)
                                    <tr>
                                        <td data-label="No. Faktur"><p class="mb-0 text-sm">{{ $order->no_faktur }}</p></td>
                                        <td data-label="Status">
                                            <span class="badge badge-sm {{ $order->status == 'Pending' ? 'bg-gradient-warning' : ($order->status == 'Diproses' ? 'bg-gradient-primary' : ($order->status == 'Dikirim' || $order->status == 'Diambil' ? 'bg-gradient-success' : 'bg-gradient-secondary')) }}">{{ $order->status }}</span>
                                        </td>
                                        <td data-label="Pembayaran">
                                            <span class="badge badge-sm {{ $order->payment_status == 'Lunas' ? 'bg-gradient-success' : ($order->payment_status == 'DP' ? 'bg-gradient-warning' : 'bg-gradient-secondary') }}">{{ $order->payment_status }}</span>
                                        </td>
                                        <td data-label="Tgl Order"><p class="mb-0 text-sm">{{ $order->tgl_order }}</p></td>
                                        <td data-label="Total Harga Jual"><p class="mb-0 text-sm">Rp {{ number_format($order->total_harga_jual, 0, ',', '.') }}</p></td>
                                        <td data-label="Aksi">
                                            <a href="/order/{{ $order->id }}/edit" class="btn btn-secondary btn-sm mb-0"><i class="fas fa-eye" aria-hidden="true"></i> Lihat</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-sm">Belum ada pesanan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
