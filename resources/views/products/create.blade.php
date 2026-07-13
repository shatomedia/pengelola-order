@extends('layouts.master')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>Tambah Produk</h6>
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
        <form action="/product" method="POST">
          @csrf
          <div class="form-group">
            <label for="inputNama">Nama</label>
            <input name="nama" type="text" class="form-control" id="inputNama" aria-describedby="inputNama" placeholder="Nama Produk">
          </div>
          <div class="form-group">
            <label for="formControlSelect">Kategori</label>
            <select required name="kategori_id" class="form-control select2" id="formControlSelect">
              <option value="">Pilih</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('kategori_id') == $category->id ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
              @endforeach

            </select>
          </div>
          <div class="form-group">
            <label for="inputHargaModal">Harga Modal</label>
            <input name="harga_modal" type="number" class="form-control" id="inputHargaModal" aria-describedby="inputHargaModal" placeholder="Harga Modal">
          </div>
          <div class="form-group">
            <label for="inputHarga">Harga Jual</label>
            <input name="harga" type="number" class="form-control" id="inputHarga" aria-describedby="inputHarga" placeholder="Harga Jual">
          </div>
          <div class="form-group">
            <label for="inputDeskripsi">Deskripsi</label>
            <input name="deskripsi" type="text" class="form-control" id="inputDeskripsi" aria-describedby="inputDeskripsi" placeholder="Deskripsi">
          </div>
          <div class="form-group">
            <label for="inputStok">Stok</label>
            <input name="stok" type="number" class="form-control" id="inputStok" aria-describedby="inputStok" placeholder="Stok">
          </div>
          <div class="form-group">
            <label for="inputSatuan">Satuan</label>
            <input name="satuan" type="text" class="form-control" id="inputSatuan" aria-describedby="inputSatuan" placeholder="Satuan">
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
