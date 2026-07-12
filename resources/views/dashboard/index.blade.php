@extends('layouts.master')

@section('content')
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
    <div class="col-12 py-4">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Penjualan Hari ini</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Aksi</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Status</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">No. Faktur</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Nama Pembeli</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Alamat</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">No HP</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Produk</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Order Via</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Tgl Order</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Tgl Kirim</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total Qty</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total Harga Jual</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Title</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Background</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Request</th>
                                <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Keterangan</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if ($ordersToday->isEmpty())
                                <tr>
                                    <td colspan="14" class="text-center">Belum ada penjualan hari ini.</td>
                                </tr>
                            @else
                                @foreach ($ordersToday as $order)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div>
                                                <a href="/order/{{ $order->id }}/edit"
                                                    class="btn btn-secondary btn-sm mb-0 px-2 btn-tooltip"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
                                                    data-container="body" data-animation="true" aria-pressed="true">
                                                    <i class="fas fa-edit text-xs" aria-hidden="true"></i>
                                                </a>
                                            </div>

                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-sm {{ $order->status == 'Pending' ? 'bg-gradient-warning' : ($order->status == 'Diproses' ? 'bg-gradient-primary' : ($order->status == 'Dikirim' || $order->status == 'Diambil' ? 'bg-gradient-success' : 'bg-gradient-secondary')) }}">{{ $order->status }}</span>
                                        </td>
                                        <td>
                                            <p class="mb-0 text-sm">{{ $order->no_faktur }}</p>
                                        </td>
                                        <td>
                                            <p class="mb-0 text-sm">{{ $order->nama_pembeli }}</p>
                                        </td>
                                        <td class="align-middle">
                                            <p class="mb-0 text-sm">{{ Str::limit($order->alamat, 50) }}</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-sm">{{ $order->no_hp }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-gradient-secondary" data-bs-toggle="modal"
                                                data-bs-target="#modal-detail-{{ $order->id }}"
                                                style="cursor: pointer">Lihat Detail Order</span>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->order_via }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->tgl_order }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->tgl_kirim }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->total_qty }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">Rp
                                                {{ number_format($order->total_harga_jual, 0, ',', '.') }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->title }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">
                                                @if ($order->background)
                                                    <a href="{{ url($order->background) }}"
                                                        target="_blank">{{ $order->background }}</a>
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->request }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->keterangan }}</p>
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
