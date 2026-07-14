@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Pengeluaran Berulang</h6>
                    <div class="d-flex mb-2 float-end">
                        @can('pengeluaran-berulang-create')
                            <form action="{{ route('pengeluaran-berulang.generate') }}" method="POST" class="d-inline me-2">
                                @csrf
                                <button type="submit" class="btn btn-outline-dark" onclick="return confirm('Generate tagihan bulan ini untuk semua template aktif yang belum dibuat?')">
                                    <i class="fas fa-sync-alt"></i> Generate Tagihan Bulan Ini
                                </button>
                            </form>
                            <a class="btn bg-gradient-warning" href="/pengeluaran-berulang/create"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Aksi</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Nama</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Kategori</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Estimasi Jumlah</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Jatuh Tempo</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Bulan Ini</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Realisasi Bulan Ini</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($templates as $template)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div>
                                                @can('pengeluaran-berulang-edit')
                                                    <a href="/pengeluaran-berulang/{{ $template->id }}/edit"
                                                        class="btn btn-secondary btn-sm mb-0 px-2 btn-tooltip"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                        <i class="fas fa-edit text-xs" aria-hidden="true"></i>
                                                    </a>
                                                @endcan
                                                @can('pengeluaran-berulang-delete')
                                                    <form action="/pengeluaran-berulang/{{ $template->id }}/delete" method="POST" class="d-inline" onsubmit="return confirm('Hapus template ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm mb-0 px-2">
                                                            <i class="fas fa-trash text-xs" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                        <td><p class="mb-0 text-sm">{{ $template->nama }}</p></td>
                                        <td><p class="mb-0 text-sm">{{ optional($template->kategori)->nama_kategori }}</p></td>
                                        <td><p class="mb-0 text-sm">Rp {{ number_format($template->jumlah_estimasi, 0, ',', '.') }}</p></td>
                                        <td><p class="mb-0 text-sm">Tanggal {{ $template->tanggal_jatuh_tempo }}</p></td>
                                        <td>
                                            <span class="badge badge-sm {{ $template->aktif ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                                {{ $template->aktif ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm {{ $template->sudah_generate ? 'bg-gradient-info' : 'bg-gradient-warning' }}">
                                                {{ $template->sudah_generate ? 'Sudah Dibuat' : 'Belum Dibuat' }}
                                            </span>
                                        </td>
                                        <td>
                                            <p class="mb-0 text-sm {{ $template->over_budget ? 'text-danger font-weight-bold' : '' }}">
                                                Rp {{ number_format($template->realisasi_bulan_ini, 0, ',', '.') }}
                                            </p>
                                            @if($template->over_budget)
                                                <span class="badge badge-sm bg-gradient-danger">Melebihi Estimasi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
