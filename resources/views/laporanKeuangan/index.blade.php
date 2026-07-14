@extends('layouts.master')

@section('content')
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-3">Laporan Keuangan</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('laporan-keuangan.index') }}" method="get">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date">Pilih Rentang Tanggal</label>
                                        <input type="text" class="form-control daterange" name="date" id="date" value="{{ $date }}" placeholder="Rentang Tanggal" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-filter" aria-hidden="true"></i> Filter</button>
                            <div class="float-end">
                                <button type="submit" name="export" value="excel" class="btn btn-outline-dark"><i class="fas fa-file-excel" aria-hidden="true"></i> Export Excel</button>
                                <button type="submit" name="export" value="pdf" class="btn btn-outline-dark"><i class="fas fa-file-pdf" aria-hidden="true"></i> Export PDF</button>
                            </div>
                        </form>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="text-sm mb-1">Total Pemasukan</p>
                                        <h5 class="text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="text-sm mb-1">Total Pengeluaran</p>
                                        <h5 class="text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="text-sm mb-1">Saldo</p>
                                        <h5 class="{{ $saldo >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format($saldo, 0, ',', '.') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Tanggal</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Jenis</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Kategori</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Sumber/Keterangan</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ordersDenganPembayaran as $orderBayar)
                                        <tr>
                                            <td><p class="mb-0 text-sm">{{ \Illuminate\Support\Carbon::parse($orderBayar->tgl_order)->isoFormat('DD MMM YYYY') }}</p></td>
                                            <td><span class="badge badge-sm bg-gradient-info">Pemasukan (Order)</span></td>
                                            <td><p class="mb-0 text-sm">-</p></td>
                                            <td><p class="mb-0 text-sm">{{ $orderBayar->no_faktur }} - {{ $orderBayar->nama_pembeli }}</p></td>
                                            <td><p class="mb-0 text-sm">Rp {{ number_format($orderBayar->jumlah_dibayar, 0, ',', '.') }}</p></td>
                                        </tr>
                                    @endforeach
                                    @foreach ($pemasukans as $pemasukan)
                                        <tr>
                                            <td><p class="mb-0 text-sm">{{ \Illuminate\Support\Carbon::parse($pemasukan->tanggal)->isoFormat('DD MMM YYYY') }}</p></td>
                                            <td><span class="badge badge-sm bg-gradient-success">Pemasukan</span></td>
                                            <td><p class="mb-0 text-sm">{{ optional($pemasukan->kategori)->nama_kategori }}</p></td>
                                            <td><p class="mb-0 text-sm">{{ $pemasukan->sumber }}</p></td>
                                            <td><p class="mb-0 text-sm">Rp {{ number_format($pemasukan->jumlah, 0, ',', '.') }}</p></td>
                                        </tr>
                                    @endforeach
                                    @foreach ($pengeluarans as $pengeluaran)
                                        <tr>
                                            <td><p class="mb-0 text-sm">{{ \Illuminate\Support\Carbon::parse($pengeluaran->tanggal)->isoFormat('DD MMM YYYY') }}</p></td>
                                            <td><span class="badge badge-sm bg-gradient-danger">Pengeluaran</span></td>
                                            <td><p class="mb-0 text-sm">{{ optional($pengeluaran->kategori)->nama_kategori }}</p></td>
                                            <td><p class="mb-0 text-sm">{{ $pengeluaran->keterangan }}</p></td>
                                            <td><p class="mb-0 text-sm">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</p></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
