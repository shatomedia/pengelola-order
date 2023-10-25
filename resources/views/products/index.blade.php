@extends('layouts.master')

@section('content')
<div class="col-12">
  <div class="card mb-4">
    <div class="card-header pb-0">
      <h6>Data Produk ({{ $products->count() }})</h6>
      <div class="text-end">
        <a class="btn bg-gradient-warning mb-4" href="/product/create"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah</a>
      </div>
      
    </div>
    <div class="card-body px-0 pt-0 pb-2">
      <div class="table-responsive p-0">
        <table class="table align-items-center justify-content-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Produk</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Produk</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kategori</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Deskripsi</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Stok</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Satuan</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($products as $product)
            <tr>
              <td>
                <div class="d-flex px-3">
                    <h6 class="mb-0 text-sm">{{ $product->kode_produk }}</h6>
                </div>
              </td>
              
              <td>
                <div class="d-flex px-3">
                    <h6 class="mb-0 text-sm">{{ $product->nama }}</h6>
                </div>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">{{ optional($product->category)->nama_kategori }}
                </p>
              </td>
              <td>
                <span class="text-xs font-weight-bold">{{ $product->harga }}</span>
              </td>
              <td>
                <span class="text-xs font-weight-bold">{{ $product->deskripsi }}</span>
              </td>
              <td class="align-middle text-center">
                <div class="d-flex align-items-center justify-content-center">
                  <span class="text-xs font-weight-bold">{{ $product->stok }}</span>
                </div>
              </td>
              <td class="align-middle text-center">
                <div class="d-flex align-items-center justify-content-center">
                  <span class="me-2 text-xs font-weight-bold">{{ $product->satuan }}</span>
                </div>
              </td>
                <td class="align-middle text-center">
                  <div class="d-flex align-items-center justify-content-center">
                    <a class="btn btn-link text-dark px-3 mb-0" href="/product/{{ $product->id }}/edit"><i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Edit</a>
                    <form action="/product/{{ $product->id }}" method="POST">
                      @method('DELETE')
                      @csrf
                      <button type="submit" class="btn btn-link text-danger text-danger px-3 mb-0">
                        <i class="far fa-trash-alt me-2" aria-hidden="true"></i>Delete
                      </button>
                  </form>
                  </div>
                  
                </td>
            </tr>    
            @endforeach
          
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection