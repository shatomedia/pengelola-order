@extends('layouts.master')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>Tambah Penjualan</h6>
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
        <form action="/order" method="POST">
          @csrf
          <div class="form-group">
            <label for="inputStatus">Status</label>
              <select name="status" class="form-control select2" id="inputStatus">
                  <option value="">Pilih</option>
                  <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                  <option value="Diproses" {{ old('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                  <option value="Dikirim" {{ old('status') == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                  <option value="Batal" {{ old('status') == 'Batal' ? 'selected' : '' }}>Batal</option>
              </select>
          </div>
          <div class="form-group">
            <label for="inputNamaPembeli">Nama Pembeli</label>
            <input name="nama_pembeli" type="text" class="form-control" id="inputNamaPembeli" aria-describedby="inputNamaPembeli" placeholder="Nama Pembeli">
          </div>
          <div class="form-group">
            <label for="inputNoHp">No HP</label>
            <input name="no_hp" type="number" class="form-control" id="inputNoHp" aria-describedby="inputNoHp" placeholder="No Hp">
          </div>
          <div class="form-group">
            <label for="select-product">Produk</label>
              <select name="product_id" id="select-product" data-placeholder="Pilih" class="custom-select w-100" style="width: 100%"></select>
          </div>
          <div class="form-group">
            <label for="inputOrderVia">Order Via</label>
            <input name="order_via" type="text" class="form-control" id="inputOrderVia" aria-describedby="inputOrderVia" placeholder="Order Via">
          </div>
          <div class="form-group">
            <label for="inputTglOrder">Tgl Order</label>
            <input name="tgl_order" type="date" class="form-control" id="inputTglOrder" aria-describedby="inputTglOrder" placeholder="Tgl Order">
          </div>
          <div class="form-group">
            <label for="inputTglKirim">Tgl Kirim</label>
            <input name="tgl_kirim" type="date" class="form-control" id="inputTglKirim" aria-describedby="inputTglKirim" placeholder="Tgl Kirim">
          </div>
          <div class="form-group">
            <label for="inputTitle">Title</label>
            <input name="title" type="text" class="form-control" id="inputTitle" aria-describedby="inputTitle" placeholder="Title">
          </div>
          <div class="form-group">
            <label for="inputBackground">Background</label>
            <input name="Background" type="text" class="form-control" id="inputBackground" aria-describedby="inputBackground" placeholder="Background">
          </div>
          <div class="form-group">
            <label for="inputRequest">Request</label>
            <input name="request" type="text" class="form-control" id="inputRequest" aria-describedby="inputRequest" placeholder="Request">
          </div>
          <div class="form-group">
            <label for="inputKeterangan">Keterangan</label>
            <input name="keterangan" type="text" class="form-control" id="inputKeterangan" aria-describedby="inputKeterangan" placeholder="Keterangan">
          </div>

          <button type="submit" class="btn bg-gradient-warning">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('custom/order/select-product.js') }}"></script>
@endsection
