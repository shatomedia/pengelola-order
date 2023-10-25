@extends('layouts.master')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Halaman Edit Product</h6>
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
          
          <form action="/product/{{ $products->id }}" method="POST">
            @method('put')
            @csrf
            <div class="form-group">
              <label for="inputNamaPoduk">Nama</label>
              <input name="nama" type="text" class="form-control" id="inputNamaPoduk" aria-describedby="inputNamaKategori" placeholder="Nama Kategori" value="{{ $products->nama }}">
            </div>
            <div class="form-group">
              <label for="inputKategori">Kategori</label>
              <input name="kategori" type="text" class="form-control" id="inputKategori" aria-describedby="inputNamaKategori" placeholder="Nama Kategori" value="{{ $products->kategori_id }}">
            </div>
            <div class="form-group">
              <label for="inputHarga">Harga</label>
              <input name="harga" type="text" class="form-control" id="inputHarga" aria-describedby="inputNamaKategori" placeholder="Nama Kategori" value="{{ $products->harga }}">
            </div>
            <div class="form-group">
              <label for="inputDeskripsi">Deskripsi</label>
              <input name="deskripsi" type="text" class="form-control" id="inputDeskripsi" aria-describedby="inputDeskripsi" placeholder="Deskripsi" value="{{ $products->deskripsi }}">
            </div>
            <div class="form-group">
              <label for="inputStok">Stok</label>
              <input name="stok" type="text" class="form-control" id="inputStok" aria-describedby="inputStok" placeholder="Stok" value="{{ $products->stok }}">
            </div>
            <div class="form-group">
              <label for="inputSatuan">Satuan</label>
              <input name="satuan" type="text" class="form-control" id="inputSatuan" aria-describedby="inputSatuan" placeholder="Satuan" value="{{ $products->satuan }}">
            </div>
            <button type="submit" class="btn bg-gradient-warning">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection