@extends('layouts.master')

@section('content')
<div class="col-12">
  <div class="card mb-4">
    <div class="card-header pb-0">
      <h6>Data Kategori Keuangan</h6>
      <div class="text-end">
        <a class="btn bg-gradient-warning mb-4" href="/kategori-keuangan/create"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah</a>
      </div>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
      <div class="table-responsive p-0">
        <table class="table align-items-center justify-content-center mb-0 table-mobile-cards">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Kategori</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jenis</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Deskripsi</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($transactionCategories as $transactionCategory)
            <tr>
              <td data-label="Nama Kategori">
                <h6 class="mb-0 text-sm">{{ $transactionCategory->nama_kategori }}</h6>
              </td>
              <td data-label="Jenis">
                <span class="badge badge-sm {{ $transactionCategory->jenis == 'pemasukan' ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                    {{ ucfirst($transactionCategory->jenis) }}
                </span>
              </td>
              <td data-label="Deskripsi">
                <span class="text-xs font-weight-bold">{{ $transactionCategory->deskripsi }}</span>
              </td>
              <td class="align-middle text-center" data-label="Aksi">
                <div class="d-flex flex-wrap align-items-center justify-content-center gap-1">
                  <a class="btn btn-sm btn-link text-dark px-2 mb-0" href="/kategori-keuangan/{{ $transactionCategory->id }}/edit"><i class="fas fa-pencil-alt text-dark me-1" aria-hidden="true"></i>Edit</a>
                  <form action="/kategori-keuangan/{{ $transactionCategory->id }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link text-danger px-2 mb-0" onclick="return confirm('Hapus kategori ini?')">
                        <i class="far fa-trash-alt me-1" aria-hidden="true"></i>Delete
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
