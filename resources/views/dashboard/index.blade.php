@extends('layouts.master')

@section('content')
    @if ($orderPiutangList->count() > 0)
        <div class="alert alert-danger d-flex justify-content-between align-items-center" role="alert">
            <span>Ada {{ $orderPiutangList->count() }} order belum lunas, total piutang Rp {{ number_format($piutang, 0, ',', '.') }}.</span>
            <a href="#tabel-piutang" class="btn btn-sm bg-gradient-dark mb-0">Lihat Detail Piutang</a>
        </div>
    @endif

    @if ($stokMenipisCount > 0)
        <div class="alert alert-danger d-flex justify-content-between align-items-center" role="alert">
            <span>Ada {{ $stokMenipisCount }} produk dengan stok menipis (&le;5).</span>
            <a href="/product" class="btn btn-sm bg-gradient-dark mb-0">Lihat Produk</a>
        </div>
    @endif

    @can('pengeluaran-berulang')
        @if ($pengeluaranBerulangOverBudgetCount > 0)
            <div class="alert alert-danger d-flex justify-content-between align-items-center" role="alert">
                <span>Ada {{ $pengeluaranBerulangOverBudgetCount }} tagihan bulanan yang melebihi estimasi bulan ini.</span>
                <a href="/pengeluaran-berulang" class="btn btn-sm bg-gradient-dark mb-0">Lihat Pengeluaran Berulang</a>
            </div>
        @endif

        @if ($pengeluaranBerulangPendingCount > 0)
            <div class="alert alert-warning d-flex justify-content-between align-items-center" role="alert">
                <span>Ada {{ $pengeluaranBerulangPendingCount }} tagihan bulanan berulang yang belum di-generate bulan ini.</span>
                <a href="/pengeluaran-berulang" class="btn btn-sm bg-gradient-dark mb-0">Lihat Pengeluaran Berulang</a>
            </div>
        @endif
    @endcan

    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Penjualan</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $orders->count() }}
                                    <span class="text-success text-sm font-weight-bolder">PCS</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Produk</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $products->count() }}
                                    <span class="text-success text-sm font-weight-bolder">PCS</span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('owner')))
            <div class="col-xl-6 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Sales</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        Rp. {{ number_format($totalPenjualan, 0, ',', '.') }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-xl-6 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Kategori</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $category->count() }}
                                        <span class="text-success text-sm font-weight-bolder">Produk</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row mt-4">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Order Bulan Ini</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalOrderBulanIni }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-shopping-cart text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Pendapatan Bulan Ini</p>
                                <h5 class="font-weight-bolder mb-0">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="fas fa-wallet text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Piutang (Belum Lunas)</p>
                                <h5 class="font-weight-bolder mb-0">Rp {{ number_format($piutang, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="fas fa-file-invoice-dollar text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Stok Menipis (&le;5)</p>
                                <h5 class="font-weight-bolder mb-0">{{ $stokMenipisCount }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                <i class="fas fa-triangle-exclamation text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('laporan-keuangan')
        <div class="row mt-4">
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Pemasukan Bulan Ini</p>
                                    <h5 class="font-weight-bolder mb-0">Rp {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="fas fa-arrow-down text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Pengeluaran Bulan Ini</p>
                                    <h5 class="font-weight-bolder mb-0">Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                    <i class="fas fa-arrow-up text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Laba Bersih Bulan Ini</p>
                                    <h5 class="font-weight-bolder mb-0 {{ $labaBersihBulanIni < 0 ? 'text-danger' : '' }}">Rp {{ number_format($labaBersihBulanIni, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="fas fa-scale-balanced text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Pemasukan vs Pengeluaran 6 Bulan Terakhir</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <div id="chartKeuangan" style="min-height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    <div class="row mt-1">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Tren Penjualan 6 Bulan Terakhir</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <div id="chartTrenPenjualan" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Status Pembayaran</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <div id="chartPaymentStatus" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Produk Terlaris</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <div id="chartProdukTerlaris" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Stok Menipis</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Produk</th>
                                    <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produkStokMenipis as $produk)
                                    <tr>
                                        <td><p class="mb-0 text-sm">{{ $produk->nama }}</p></td>
                                        <td><span class="badge bg-gradient-danger">{{ $produk->stok }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-sm">Semua stok produk aman.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 py-4" id="tabel-piutang">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Piutang / Order Belum Lunas</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table table-bordered mb-0 table-mobile-cards">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">No. Faktur</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Nama Pembeli</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">No HP</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Tgl Order</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Umur (hari)</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Dibayar</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Sisa</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orderPiutangList as $orderPiutang)
                                <tr>
                                    <td data-label="No. Faktur"><p class="mb-0 text-sm">{{ $orderPiutang->no_faktur }}</p></td>
                                    <td data-label="Nama Pembeli"><p class="mb-0 text-sm">{{ $orderPiutang->nama_pembeli }}</p></td>
                                    <td data-label="No HP" class="align-middle text-center"><span class="text-secondary text-sm">{{ $orderPiutang->no_hp }}</span></td>
                                    <td data-label="Tgl Order"><p class="mb-0 text-sm">{{ $orderPiutang->tgl_order }}</p></td>
                                    <td data-label="Umur (hari)" class="align-middle text-center">
                                        <span class="text-sm">{{ \Illuminate\Support\Carbon::parse($orderPiutang->tgl_order)->diffInDays(now()) }}</span>
                                    </td>
                                    <td data-label="Total"><p class="mb-0 text-sm">Rp {{ number_format($orderPiutang->total_harga_jual, 0, ',', '.') }}</p></td>
                                    <td data-label="Dibayar"><p class="mb-0 text-sm">Rp {{ number_format($orderPiutang->jumlah_dibayar, 0, ',', '.') }}</p></td>
                                    <td data-label="Sisa"><p class="mb-0 text-sm text-danger font-weight-bold">Rp {{ number_format($orderPiutang->total_harga_jual - $orderPiutang->jumlah_dibayar, 0, ',', '.') }}</p></td>
                                    <td data-label="Status">
                                        <span class="badge badge-sm {{ $orderPiutang->payment_status == 'DP' ? 'bg-gradient-warning' : 'bg-gradient-danger' }}">{{ $orderPiutang->payment_status }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada piutang, semua order sudah lunas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 py-4">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Penjualan Hari ini</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table table-bordered mb-0 table-mobile-cards">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Aksi</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Status</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">No. Faktur</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Nama Pembeli</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">No HP</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total Qty</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total Harga Jual</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($ordersToday->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada penjualan hari ini.</td>
                                </tr>
                            @else
                                @foreach ($ordersToday as $order)
                                    <tr>
                                        <td class="align-middle text-center" data-label="Aksi">
                                            <div>
                                                <a href="/order/{{ $order->id }}/edit"
                                                    class="btn btn-secondary btn-sm mb-0 px-2 btn-tooltip"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
                                                    data-container="body" data-animation="true" aria-pressed="true">
                                                    <i class="fas fa-edit text-xs" aria-hidden="true"></i>
                                                </a>
                                            </div>

                                        </td>
                                        <td data-label="Status">
                                            <span
                                                class="badge badge-sm {{ $order->status == 'Pending' ? 'bg-gradient-warning' : ($order->status == 'Diproses' ? 'bg-gradient-primary' : ($order->status == 'Dikirim' || $order->status == 'Diambil' ? 'bg-gradient-success' : 'bg-gradient-secondary')) }}">{{ $order->status }}</span>
                                        </td>
                                        <td data-label="No. Faktur">
                                            <p class="mb-0 text-sm">{{ $order->no_faktur }}</p>
                                        </td>
                                        <td data-label="Nama Pembeli">
                                            <p class="mb-0 text-sm">{{ $order->nama_pembeli }}</p>
                                        </td>
                                        <td class="align-middle text-center" data-label="No HP">
                                            <span class="text-secondary text-sm">{{ $order->no_hp }}</span>
                                        </td>
                                        <td data-label="Total Qty">
                                            <p class="text-sm mb-0">{{ $order->total_qty }}</p>
                                        </td>
                                        <td data-label="Total Harga Jual">
                                            <p class="text-sm mb-0">Rp
                                                {{ number_format($order->total_harga_jual, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="align-middle text-center" data-label="Detail">
                                            <span class="badge bg-gradient-secondary" data-bs-toggle="modal"
                                                data-bs-target="#modal-detail-{{ $order->id }}"
                                                style="cursor: pointer">Lihat Detail</span>
                                        </td>
                                    </tr>
                                    {{-- Modal --}}
                                    <div class="modal fade" id="modal-detail-{{ $order->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Detail Order
                                                        {{ $order->no_faktur }}</h5>
                                                    <button type="button" class="btn-close text-dark"
                                                        data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <dl>
                                                        @foreach ($order->detailOrders as $detailOrder)
                                                            <dt>Nama Item : {{ optional($detailOrder->produk)->nama }}</dt>
                                                            <dd>Qty : {{ $detailOrder->qty }}
                                                                {{ optional($detailOrder->produk)->satuan }}</dd>
                                                        @endforeach
                                                    </dl>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn bg-gradient-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card z-index-2 mt-4">
        <div class="card-body p-3">
            <div class="border-radius-lg py-3 pe-1 mb-3">
                <div class="chart">
                    <div id="chartContainer"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.chart('chartTrenPenjualan', {
                chart: { type: 'areaspline', backgroundColor: 'rgba(255, 255, 255, 0.1)', height: 300 },
                title: { text: null },
                xAxis: { categories: {!! json_encode($trenLabels) !!} },
                yAxis: { title: { text: 'Rp' }, min: 0 },
                tooltip: {
                    formatter: function () {
                        return '<b>' + this.x + '</b><br/>Rp ' + Highcharts.numberFormat(this.y, 0, ',', '.');
                    }
                },
                series: [{
                    name: 'Total Penjualan',
                    data: {!! json_encode($trenData) !!},
                    color: '#17c1e8'
                }]
            });

            @can('laporan-keuangan')
            Highcharts.chart('chartKeuangan', {
                chart: { type: 'column', backgroundColor: 'rgba(255, 255, 255, 0.1)', height: 300 },
                title: { text: null },
                xAxis: { categories: {!! json_encode($keuanganTrenLabels) !!} },
                yAxis: { title: { text: 'Rp' }, min: 0 },
                tooltip: {
                    formatter: function () {
                        return '<b>' + this.x + '</b><br/>' + this.series.name + ': Rp ' + Highcharts.numberFormat(this.y, 0, ',', '.');
                    }
                },
                series: [
                    { name: 'Pemasukan', data: {!! json_encode($keuanganTrenPemasukan) !!}, color: '#82d616' },
                    { name: 'Pengeluaran', data: {!! json_encode($keuanganTrenPengeluaran) !!}, color: '#ea0606' }
                ]
            });
            @endcan

            Highcharts.chart('chartPaymentStatus', {
                chart: { type: 'pie', backgroundColor: 'rgba(255, 255, 255, 0.1)', height: 300 },
                title: { text: null },
                tooltip: { pointFormat: '{series.name}: <b>{point.y} order ({point.percentage:.1f}%)</b>' },
                series: [{
                    name: 'Order',
                    colorByPoint: true,
                    data: [
                        { name: 'Lunas', y: {{ (int) ($paymentStatusCounts['Lunas'] ?? 0) }}, color: '#82d616' },
                        { name: 'DP', y: {{ (int) ($paymentStatusCounts['DP'] ?? 0) }}, color: '#fbcf33' },
                        { name: 'Belum Bayar', y: {{ (int) ($paymentStatusCounts['Belum Bayar'] ?? 0) }}, color: '#ea0606' }
                    ]
                }]
            });

            Highcharts.chart('chartProdukTerlaris', {
                chart: { type: 'bar', backgroundColor: 'rgba(255, 255, 255, 0.1)', height: 300 },
                title: { text: null },
                xAxis: { categories: {!! json_encode($produkTerlaris->pluck('nama')) !!} },
                yAxis: { title: { text: 'Qty Terjual' }, min: 0 },
                legend: { enabled: false },
                series: [{
                    name: 'Qty Terjual',
                    data: {!! json_encode($produkTerlaris->pluck('total_qty')) !!},
                    color: '#7928CA'
                }]
            });

            const chartData = {!! json_encode($hasilApriori) !!};

            const categories = chartData.map(function (data) {
                return data.nama_produk;
            });

            const dataSupport = chartData.map(function (data) {
                return parseFloat(data.persentase_hasil_support);
            });

            const dataConfidence = chartData.map(function (data) {
                return parseFloat(data.persentase_hasil_confidence);
            });

            Highcharts.chart('chartContainer', {
                chart: {
                    type: 'column',
                    backgroundColor: 'rgba(255, 255, 255, 0.1)' // Warna latar belakang
                },
                title: {
                    text: 'Hasil Apriori',
                },
                xAxis: {
                    categories: categories,
                },
                yAxis: {
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: 'Persentase',
                    }
                },
                tooltip: {
                    formatter: function () {
                        return '<b>' + this.x + '</b><br/>' +
                            this.series.name + ': ' + this.y;
                    }
                },
                series: [{
                    name: 'Hasil Support',
                    data: dataSupport,
                    color: {
                        linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
                        stops: [
                            [0, '#fbcf33'], // Warna awal
                            [1, '#f53939'] // Warna akhir
                        ]
                    }
                }, {
                    name: 'Hasil Confidence',
                    data: dataConfidence,
                    color: {
                        linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
                        stops: [
                            [0, '#7928CA'], // Warna awal
                            [1, '#FF0080'] // Warna akhir
                        ]
                    }
                }]
            });
        });
    </script>
@endsection
