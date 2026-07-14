@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Data Pemasukan</h6>
                    <div class="d-flex mb-2 float-end">
                        @can('pemasukan-create')
                            <a class="btn bg-gradient-warning" href="/pemasukan/create"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Aksi</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Tanggal</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Kategori</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Sumber</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Order Terkait</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Jumlah</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pemasukans as $pemasukan)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div>
                                                @can('pemasukan-edit')
                                                    <a href="/pemasukan/{{ $pemasukan->id }}/edit"
                                                        class="btn btn-secondary btn-sm mb-0 px-2 btn-tooltip"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                        <i class="fas fa-edit text-xs" aria-hidden="true"></i>
                                                    </a>
                                                @endcan
                                                @can('pemasukan-delete')
                                                    <form action="/pemasukan/{{ $pemasukan->id }}/delete" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm mb-0 px-2">
                                                            <i class="fas fa-trash text-xs" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                        <td><p class="mb-0 text-sm">{{ \Illuminate\Support\Carbon::parse($pemasukan->tanggal)->isoFormat('DD MMM YYYY') }}</p></td>
                                        <td><p class="mb-0 text-sm">{{ optional($pemasukan->kategori)->nama_kategori }}</p></td>
                                        <td><p class="mb-0 text-sm">{{ $pemasukan->sumber }}</p></td>
                                        <td><p class="mb-0 text-sm">{{ optional($pemasukan->order)->no_faktur }}</p></td>
                                        <td><p class="mb-0 text-sm">Rp {{ number_format($pemasukan->jumlah, 0, ',', '.') }}</p></td>
                                        <td><p class="mb-0 text-sm">{{ $pemasukan->keterangan }}</p></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer pagination float-end">
                        {{ $pemasukans->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
