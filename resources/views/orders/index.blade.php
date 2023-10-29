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
                        <div class="col-5">
                            <form action="{{ url('/template-penjualan') }}" method="GET">
                                @csrf
                                <button type="submit" class="btn btn-outline-info mb-0">
                                    <i class="fas fa-download"></i> Unduh Template Excel
                                </button>
                            </form>
                        </div>
                        <div class="col-7">
                            <form action="{{ url('/order-import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input name="template" type="file" required class="form-control" placeholder="Upload File" aria-label="Upload" aria-describedby="button-addon4">
                                    <button class="btn bg-gradient-info mb-0" type="submit">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-bordered mb-0">
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
                                <th class="text-secondary opacity-7">Opsi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($orders as $order)
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
                                    <td class="align-middle">
                                        <a href="/order/{{ $order->id }}/edit" class="text-secondary text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                                {{--Modal--}}
                                <div class="modal fade" id="modal-detail-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Detail Order {{ $order->no_faktur }}</h5>
                                                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <dl>
                                                    @foreach($order->detailOrders as $detailOrder)
                                                        <dt>Nama Item : {{ optional($detailOrder->produk)->nama }}</dt>
                                                        <dd>Qty : {{ $detailOrder->qty }} {{ optional($detailOrder->produk)->satuan }}</dd>
                                                    @endforeach
                                                </dl>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
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
