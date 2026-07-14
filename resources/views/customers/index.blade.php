@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Data Pelanggan</h6>
                </div>
                <div class="card-body pt-0">
                    <form method="GET" class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama / no HP" value="{{ $search }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn bg-gradient-info mb-0 w-100"><i class="fas fa-search" aria-hidden="true"></i> Cari</button>
                        </div>
                    </form>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-bordered mb-0 table-mobile-cards">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Nama</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">No HP</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Alamat</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Jumlah Order</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total Belanja</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td data-label="Nama"><p class="mb-0 text-sm">{{ $customer->nama }}</p></td>
                                        <td data-label="No HP"><span class="text-secondary text-sm">{{ $customer->no_hp }}</span></td>
                                        <td data-label="Alamat"><p class="mb-0 text-sm">{{ Str::limit($customer->alamat, 50) }}</p></td>
                                        <td data-label="Jumlah Order"><p class="mb-0 text-sm">{{ $customer->orders_count }}</p></td>
                                        <td data-label="Total Belanja"><p class="mb-0 text-sm">Rp {{ number_format($customer->orders_sum_total_harga_jual ?? 0, 0, ',', '.') }}</p></td>
                                        <td data-label="Aksi">
                                            <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-secondary btn-sm mb-0"><i class="fas fa-history" aria-hidden="true"></i> Riwayat</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer pagination float-end">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
