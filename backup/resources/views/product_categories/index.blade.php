@extends('layouts.master')

@section('content')
<div class="col-12">
  <div class="card mb-4">
    <div class="card-header pb-0">
      <h6>Data Kategori Produk</h6>
      <div class="text-end">
        <a class="btn bg-gradient-warning mb-4" href="/product-category/create"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah</a>
      </div>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
      <div class="table-responsive p-0">
        <table class="table align-items-center justify-content-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Kategori</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Deskripsi</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($productcategories as $productcategory)
            <tr>
              <td>
                <div class="d-flex px-3">
                    <h6 class="mb-0 text-sm">{{ $productcategory->nama_kategori }}</h6>
                </div>
              </td>
              
              <td>
                <span class="text-xs font-weight-bold">{{ $productcategory->deskripsi }}</span>
              </td>
              
                <td class="align-middle text-center">
                  <div class="d-flex align-items-center justify-content-center">
                    <a class="btn btn-link text-dark px-3 mb-0" href="/product-category/{{ $productcategory->id }}/edit"><i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Edit</a>
                    <form action="/product-category/{{ $productcategory->id }}" method="POST">
                      @method('DELETE')
                      @csrf
                      <button type="submit" class="btn btn-link text-danger text-gradient px-3 mb-0">
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