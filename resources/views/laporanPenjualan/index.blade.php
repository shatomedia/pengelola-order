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
                        <form action="{{ route('laporan-penjualan.index') }}" method="get">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date">Pilih Rentang Tanggal Order</label>
                                        <input type="text" class="form-control daterange" name="date" id="date" value="{{ !$date ? '' : $date }}" placeholder="Rentang Tanggal" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="select-list-products">Pilih Produk</label>
                                        <select name="product_id" id="select-list-products" class="form-control select2" data-placeholder="Pilih" style="width: 100%">
                                            <option value="{{ request('product_id') ? request('product_id') : '' }}">{{ request('product_id') ? $product->nama : '' }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="select-list-status">Pilih Status</label>
                                        <select name="status" id="select-list-status" class="form-control select2" style="width: 100%">
                                            <option value="">Semua</option>
                                            @foreach($listStatus as $status)
                                                <option value="{{ $status->status }}" {{ request('status') == $status->status ? 'selected' : '' }}>{{ $status->status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-filter" aria-hidden="true"></i> Filter</button>
                            <div class="float-end">
                                <button type="submit" name="export" value="true" class="btn btn-outline-dark"><i class="fas fa-file-export" aria-hidden="true"></i> Export</button>
                            </div>
                        </form>
                    </div>
                    <hr>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-gradient-info text-white">
                                    <div class="card-body p-3">
                                        <p class="mb-0 text-sm opacity-8">Total Pendapatan</p>
                                        <h5 class="text-white mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-gradient-secondary text-white">
                                    <div class="card-body p-3">
                                        <p class="mb-0 text-sm opacity-8">Estimasi Modal</p>
                                        <h5 class="text-white mb-0">Rp {{ number_format($totalModal, 0, ',', '.') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card {{ $totalLaba >= 0 ? 'bg-gradient-success' : 'bg-gradient-danger' }} text-white">
                                    <div class="card-body p-3">
                                        <p class="mb-0 text-sm opacity-8">Estimasi Laba</p>
                                        <h5 class="text-white mb-0">Rp {{ number_format($totalLaba, 0, ',', '.') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-secondary mt-2 mb-0">
                            * Estimasi modal dihitung dari harga modal produk saat ini dikali qty terjual pada periode ini.
                            Produk yang belum diisi harga modalnya akan dianggap Rp 0 pada perhitungan ini.
                        </p>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Nama Pembeli</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Alamat</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">No HP</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total Produk</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Order Via</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Tgl Order</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Tgl Kirim</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total Qty</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total Harga Jual
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Title</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Background</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Request</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>
                                                <span class="badge badge-sm {{ $order->status == 'Pending' ? 'bg-gradient-warning' : ($order->status == 'Diproses' ? 'bg-gradient-primary' : ($order->status == 'Dikirim' ? 'bg-gradient-success' : ($order->status == 'Diambil' ? 'bg-gradient-secondary' : 'bg-gradient-danger'))) }}">{{ $order->status }}</span>
                                            </td>
                                            <td>
                                                <p class="mb-0 text-sm">{{ $order->nama_pembeli }}</p>
                                            </td>
                                            <td class="align-middle">
                                                <p class="mb-0 text-sm">{{ Str::limit($order->alamat, 50) }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <p class="text-sm mb-0">{{ $order->no_hp }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <p class="text-sm mb-0">{{ $order->detail_orders_count }} Item</p>
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
                                                <p class="text-sm mb-0">Rp
                                                    {{ number_format($order->total_harga_jual, 0, ',', '.') }}</p>
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
                @if($product)
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-3">Laporan Penjualan {{ $product->nama }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                            <tr>
                                <th>Tgl Order</th>
                                <th>Jumlah Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($monthList as $key => $month)
                                <tr>
                                    <td>{{ $month }}</td>
                                    <td>{{ $jumlahQty[$key] }} Qty</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/product/product-list.js') }}"></script>
@endsection
