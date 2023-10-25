@extends('layouts.master')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>halaman Edit penjualan</h6>
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
          <form action="/order/{{ $orders->id }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="inputStatus">Status</label>
              <input name="status" type="text" class="form-control" id="inputStatus" aria-describedby="inputStatus" placeholder="Status" value="{{ $orders->status }}">
            </div>
            <div class="form-group">
              <label for="inputNamaPembeli">Nama Pembeli</label>
              <input name="nama_pembeli" type="text" class="form-control" id="inputNamaPembeli" aria-describedby="inputNamaPembeli" placeholder="Nama Pembeli" value="{{ $orders->nama_pembeli }}">
            </div>
            <div class="form-group">
              <label for="inputNoHp">No HP</label>
              <input name="no_hp" type="text" class="form-control" id="inputNoHp" aria-describedby="inputNoHp" placeholder="No Hp" value="{{ $orders->no_hp }}">
            </div>
            <div class="form-group">
              <label for="inputProduk">Produk</label>
              <input name="produk_id" type="text" class="form-control" id="inputProduk" aria-describedby="inputProduk" placeholder="Produk" value="{{ $orders->produk_id }}">
            </div>
            <div class="form-group">
              <label for="inputOrderVia">Order Via</label>
              <input name="order_via" type="text" class="form-control" id="inputOrderVia" aria-describedby="inputOrderVia" placeholder="Order Via" value="{{ $orders->order_via }}">
            </div>
            <div class="form-group">
              <label for="inputTglOrder">Tgl Order</label>
              <input name="tgl_order" type="text" class="form-control" id="inputTglOrder" aria-describedby="inputTglOrder" placeholder="Tgl Order" value="{{ $orders->tgl_order }}">
            </div>
            <div class="form-group">
              <label for="inputTglKirim">Tgl Kirim</label>
              <input name="tgl_kirim" type="text" class="form-control" id="inputTglKirim" aria-describedby="inputTglKirim" placeholder="Tgl Kirim" value="{{ $orders->tgl_kirim }}">
            </div>
            <div class="form-group">
              <label for="inputTitle">Title</label>
              <input name="title" type="text" class="form-control" id="inputTitle" aria-describedby="inputTitle" placeholder="Title" value="{{ $orders->title }}">
            </div>
            <div class="form-group">
              <label for="inputBackground">Background</label>
              <input name="Background" type="text" class="form-control" id="inputBackground" aria-describedby="inputBackground" placeholder="Background" value="{{ $orders->background }}">
            </div>
            <div class="form-group">
              <label for="inputRequest">Request</label>
              <input name="request" type="text" class="form-control" id="inputRequest" aria-describedby="inputRequest" placeholder="Request" value="{{ $orders->request }}">
            </div>
            <div class="form-group">
              <label for="inputKeterangan">Keterangan</label>
              <input name="keterangan" type="text" class="form-control" id="inputKeterangan" aria-describedby="inputKeterangan" placeholder="Keterangan" value="{{ $orders->keterangan }}">
            </div>
  
            <button type="submit" class="btn bg-gradient-warning">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection