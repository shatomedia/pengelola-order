@extends('layouts.master')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>Tambah Kategori Produk</h6>
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
        <form action="/product-category" method="POST">
          @csrf
          <div class="form-group">
            <label for="inputNamaKategori">Nama Kategori</label>
            <input name="nama_kategori" type="text" class="form-control" id="inputNamaKategori" aria-describedby="inputNamaKategori" placeholder="Nama Kategori">
          </div>
          <div class="form-group">
            <label for="inputDeskripsi">Deskripsi</label>
            <input name="deskripsi" type="text" class="form-control" id="inputDeskripsi" aria-describedby="inputDeskripsi" placeholder="Deskripsi">
          </div>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save" aria-hidden="true"></i> Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection