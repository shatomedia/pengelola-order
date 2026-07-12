@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-8">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>{{ $title }}</h6>
                </div>
                <div class="card-body px-3 pt-0 pb-2">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>

                    @endif
                    <form action="{{ route('order.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="inputStatus">Status</label>
                            <select name="status" class="form-control select2" id="inputStatus" required>
                                <option value="">Pilih</option>
                                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Diproses" {{ $order->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="Dikirim" {{ $order->status == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="Batal" {{ $order->status == 'Batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputNamaPembeli">Nama Pembeli</label>
                            <input name="nama_pembeli" type="text" class="form-control" id="inputNamaPembeli" aria-describedby="inputNamaPembeli" placeholder="Nama Pembeli"  value="{{ $order->nama_pembeli }}" required>
                        </div>
                        <div class="form-group">
                            <label for="inputNoHp">No HP</label>
                            <input name="no_hp" type="number" class="form-control" id="inputNoHp" aria-describedby="inputNoHp" placeholder="No Hp" value="{{ $order->no_hp }}" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control" placeholder="Alamat Lengkap" required>{{ $order->alamat }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="inputOrderVia">Order Via</label>
                            <input name="order_via" type="text" class="form-control" id="inputOrderVia" aria-describedby="inputOrderVia" value="{{ $order->order_via }}" placeholder="Order Via" required>
                        </div>
                        <div class="form-group">
                            <label for="inputTglOrder">Tgl Order</label>
                            <input name="tgl_order" type="text" class="form-control datepicker" id="inputTglOrder" aria-describedby="inputTglOrder"value="{{ $order->tgl_order }}" placeholder="Tgl Order" required>
                        </div>
                        <div class="form-group">
                            <label for="inputTglKirim">Tgl Kirim</label>
                            <input name="tgl_kirim" type="text" class="form-control datepicker" id="inputTglKirim" aria-describedby="inputTglKirim" value="{{ $order->tgl_kirim }}" placeholder="Tgl Kirim" required>
                        </div>
                        <div class="form-group">
                            <label for="inputTitle">Title</label>
                            <input name="title" type="text" class="form-control" id="inputTitle" aria-describedby="inputTitle" placeholder="Title" value="{{ $order->title }}" required>
                        </div>
                        <div class="form-group">
                            <label for="inputBackground">Background</label>
                            <input name="background" type="text" class="form-control" id="inputBackground" aria-describedby="inputBackground" value="{{ $order->background }}" placeholder="Background" required>
                        </div>
                        <div class="form-group">
                            <label for="inputRequest">Request</label>
                            <input name="request" type="text" class="form-control" id="inputRequest" aria-describedby="inputRequest" placeholder="Request" value="{{ $order->request }}" required>
                        </div>
                        <div class="form-group">
                            <label for="inputKeterangan">Keterangan</label>
                            <input name="keterangan" type="text" class="form-control" id="inputKeterangan" aria-describedby="inputKeterangan" placeholder="Keterangan" value="{{ $order->keterangan }}" required>
                        </div>

                        <button type="submit" class="btn bg-gradient-warning">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Detail Produk</h6>
                </div>
                <div class="card-body px-3 pt-0 pb-2 table-responsive">
                    <table class="table table-bordered" style="width: 100%">
                        <thead>
                        <tr>
                            <th>Nama Item</th>
                            <th>Sub Qty</th>
                            <th>Sub Total</th>
                            <th>Opsi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->detailOrders as $detailOrder)
                            <tr>
                                <td>{{ optional($detailOrder->produk)->nama }}</td>
                                <td>{{ $detailOrder->qty }} {{ optional($detailOrder->produk)->satuan }}</td>
                                <td>Rp {{ number_format($detailOrder->sub_total,0,',','.') }}</td>
                                <td>
                                    <form action="{{ route('detail-order.delete', $detailOrder->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <button type="submit" class="btn btn-sm btn-danger btn-delete-confirm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer pb-0">
                    <button type="button" class="btn btn-sm bg-gradient-success" data-bs-toggle="modal" data-bs-target="#modal-create">Tambah Item</button>
                </div>
                <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalMessageTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Produk Baru Ke {{ $order->no_faktur }}</h5>
                                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <form action="{{ route('detail-order.store', $order->id) }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <div class="form-group">
                                        <label for="produk_id" class="col-form-label">Pilih Produk</label>
                                        <select name="produk_id" id="produk_id" class="form-control select2" style="width: 100%" required>
                                            <option value="">Pilih</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" {{ old('produk_id') == $product->id ? 'selected' : '' }}>{{ $product->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="qty" class="col-form-label">Qty</label>
                                        <input type="number" name="qty" class="form-control" id="qty" value="{{ old('qty') }}" placeholder="Qty">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn bg-gradient-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
