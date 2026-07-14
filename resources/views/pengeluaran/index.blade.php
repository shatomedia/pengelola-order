@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Data Pengeluaran</h6>
                    <div class="d-flex mb-2 float-end">
                        @can('pengeluaran-create')
                            <a class="btn bg-gradient-warning" href="/pengeluaran/create"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah</a>
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
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Jumlah</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengeluarans as $pengeluaran)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div>
                                                @can('pengeluaran-edit')
                                                    <a href="/pengeluaran/{{ $pengeluaran->id }}/edit"
                                                        class="btn btn-secondary btn-sm mb-0 px-2 btn-tooltip"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                        <i class="fas fa-edit text-xs" aria-hidden="true"></i>
                                                    </a>
                                                @endcan
                                                @can('pengeluaran-delete')
                                                    <form action="/pengeluaran/{{ $pengeluaran->id }}/delete" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm mb-0 px-2">
                                                            <i class="fas fa-trash text-xs" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                        <td><p class="mb-0 text-sm">{{ \Illuminate\Support\Carbon::parse($pengeluaran->tanggal)->isoFormat('DD MMM YYYY') }}</p></td>
                                        <td><p class="mb-0 text-sm">{{ optional($pengeluaran->kategori)->nama_kategori }}</p></td>
                                        <td><p class="mb-0 text-sm">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</p></td>
                                        <td><p class="mb-0 text-sm">{{ $pengeluaran->keterangan }}</p></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer pagination float-end">
                        {{ $pengeluarans->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
