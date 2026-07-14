@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Data Penjualan</h6>
                    <div class="d-flex mb-2 float-end">
                        <a class="btn bg-gradient-warning " href="/order/create"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah</a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-12 col-md-5 mb-2 mb-md-0">
                            <form action="{{ url('/template-penjualan') }}" method="GET">
                                @csrf
                                <button type="submit" class="btn btn-outline-info mb-0 w-100 w-md-auto">
                                    <i class="fas fa-download"></i> Unduh Template Excel
                                </button>
                            </form>
                        </div>
                        <div class="col-12 col-md-7">
                            <form action="{{ url('/order-import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input name="template" type="file" required class="form-control"
                                        placeholder="Upload File" aria-label="Upload" aria-describedby="button-addon4">
                                    <button class="btn bg-gradient-info mb-0" type="submit"><i class="fas fa-upload" aria-hidden="true"></i> Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="d-flex align-items-center px-3 mb-2">
                        <form method="GET" class="d-flex align-items-center">
                            <label for="per_page" class="text-sm mb-0 me-2">Tampilkan</label>
                            <select name="per_page" id="per_page" class="form-control form-control-sm" style="width: auto" onchange="this.form.submit()">
                                @foreach ([10, 25, 50, 100] as $option)
                                    <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            <span class="text-sm ms-2">order per halaman</span>
                        </form>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table table-bordered mb-0 table-mobile-cards">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Aksi</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Pembayaran</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">No. Faktur</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Nama Pembeli</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Tgl Order</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Tgl Kirim</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total Qty</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total Harga Jual</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="align-middle text-center" data-label="Aksi" style="min-width: 110px;">
                                            <div class="d-grid gap-1">
                                                <a href="/order/{{ $order->id }}/edit"
                                                    class="btn btn-sm btn-secondary mb-0"
                                                    data-container="body" data-animation="true" aria-pressed="true">
                                                    <i class="fas fa-edit me-1" aria-hidden="true"></i> Edit
                                                </a>
                                                <a href="{{ route('order.invoice', $order->id) }}" target="_blank"
                                                    class="btn btn-sm btn-dark mb-0"
                                                    data-container="body" data-animation="true" aria-pressed="true">
                                                    <i class="fas fa-file-invoice me-1" aria-hidden="true"></i> Invoice
                                                </a>
                                            </div>
                                        </td>
                                        <td data-label="Status">
                                            <span
                                                class="badge badge-sm {{ $order->status == 'Pending' ? 'bg-gradient-warning' : ($order->status == 'Diproses' ? 'bg-gradient-primary' : ($order->status == 'Dikirim' || $order->status == 'Diambil' ? 'bg-gradient-success' : 'bg-gradient-secondary')) }}">{{ $order->status }}</span>
                                        </td>
                                        <td data-label="Pembayaran">
                                            <span
                                                class="badge badge-sm {{ $order->payment_status == 'Lunas' ? 'bg-gradient-success' : ($order->payment_status == 'DP' ? 'bg-gradient-warning' : 'bg-gradient-secondary') }}">{{ $order->payment_status }}</span>
                                        </td>
                                        <td data-label="No. Faktur">
                                            <p class="mb-0 text-sm">{{ $order->no_faktur }}</p>
                                        </td>
                                        <td data-label="Nama Pembeli">
                                            <p class="mb-0 text-sm">{{ $order->nama_pembeli }}</p>
                                        </td>
                                        <td data-label="Tgl Order">
                                            <p class="text-sm mb-0">{{ $order->tgl_order }}</p>
                                        </td>
                                        <td data-label="Tgl Kirim">
                                            <p class="text-sm mb-0">{{ $order->tgl_kirim }}</p>
                                        </td>
                                        <td data-label="Total Qty">
                                            <p class="text-sm mb-0">{{ $order->total_qty }}</p>
                                        </td>
                                        <td data-label="Total Harga Jual">
                                            <p class="text-sm mb-0">Rp
                                                {{ number_format($order->total_harga_jual, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="align-middle text-center" data-label="Detail">
                                            <span class="badge bg-gradient-secondary" data-bs-toggle="modal"
                                                data-bs-target="#modal-detail-{{ $order->id }}"
                                                style="cursor: pointer">Lihat Detail</span>
                                        </td>
                                    </tr>
                                    {{-- Modal --}}
                                    <div class="modal fade" id="modal-detail-{{ $order->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Detail Order
                                                        {{ $order->no_faktur }}</h5>
                                                    <button type="button" class="btn-close text-dark"
                                                        data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <dl>
                                                        <dt>Alamat</dt>
                                                        <dd>{{ $order->alamat }}</dd>
                                                        <dt>Order Via</dt>
                                                        <dd>{{ $order->order_via }}</dd>
                                                        <dt>Title</dt>
                                                        <dd>{{ $order->title }}</dd>
                                                        <dt>Background</dt>
                                                        <dd>
                                                            @if($order->background)
                                                                <a href="{{ url($order->background) }}" target="_blank">{{ $order->background }}</a>
                                                            @endif
                                                        </dd>
                                                        <dt>Request</dt>
                                                        <dd>{{ $order->request }}</dd>
                                                        <dt>Keterangan</dt>
                                                        <dd>{{ $order->keterangan }}</dd>
                                                        <hr>
                                                        <dt>Produk</dt>
                                                        @foreach ($order->detailOrders as $detailOrder)
                                                            <dd>{{ optional($detailOrder->produk)->nama }} &mdash; Qty : {{ $detailOrder->qty }}
                                                                {{ optional($detailOrder->produk)->satuan }}</dd>
                                                        @endforeach
                                                    </dl>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn bg-gradient-secondary"
                                                        data-bs-dismiss="modal"><i class="fas fa-times" aria-hidden="true"></i> Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer pagination float-end">
                        {{ $orders->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
