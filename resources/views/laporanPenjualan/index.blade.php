@extends('layouts.master')

@section('content')
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-3">Laporan Penjualan</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/laporan-penjualan') }}" method="get">
                            <div class="form-group">
                                <label for="date"></label>
                                <input type="text" class="form-control daterange" name="date" id="date" value="{{ !$date ? '' : $date }}" placeholder="Rentang Tanggal">
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </div>
                    <hr>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxser opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxser opacity-7 ps-2">Nama Pembeli</th>
                                    <th class="text-uppercase text-secondary text-xxser opacity-7 ps-2">Alamat</th>
                                    <th class="text-uppercase text-secondary text-xxser opacity-7 ps-2">No HP</th>
                                    <th class="text-uppercase text-secondary text-xxser opacity-7 ps-2">Produk</th>
                                    <th class="text-uppercase text-secondary text-xxser opacity-7 ps-2">Order Via</th>
                                    <th class="text-uppercase text-secondary text-xxser opacity-7 ps-2">Tgl Order</th>
                                    <th class="text-uppercase text-secondary text-xxser opacity-7 ps-2">Tgl Kirim</th>
                                    <th class="text-uppercase text-secondary text-xxser opacity-7 ps-2">Total Qty</th>
                                    <th class="text-uppercase text-secondary text-xxser opacity-7 ps-2">Total Harga Jual</th>
                                    <th class="text-uppercase text-secondary text-xxser opacity-7 ps-2">Title</th>
                                    <th class="text-uppercase text-secondary text-xxser opacity-7 ps-2">Background</th>
                                    <th class="text-uppercase text-secondary text-xxser opacity-7 ps-2">Request</th>
                                    <th class="text-uppercase text-secondary text-xxser opacity-7 ps-2">Keterangan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <span class="badge badge-sm {{ $order->status == 'Pending' ? 'bg-gradient-warning' : ($order->status == 'Diproses' ? 'bg-gradient-primary' : ($order->status == 'Dikirim' ? 'bg-gradient-success' : 'bg-gradient-secondary')) }}">{{ $order->status }}</span>
                                        </td>
                                        <td>
                                            <p class="mb-0 text-sm">{{ $order->nama_pembeli }}</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <p class="mb-0 text-sm">{{ $order->alamat }}</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-sm">{{ $order->no_hp }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-gradient-secondary" data-bs-toggle="modal" data-bs-target="#modal-detail-{{ $order->id }}" style="cursor: pointer">Lihat Detail Order</span>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->order_via }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->tgl_order }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->tgl_kirim }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->total_qty }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">Rp {{ number_format($order->total_harga_jual,0,',','.') }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->title }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->background }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->request }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->keterangan }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer pagination float-end">
                        {{ $orders->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
