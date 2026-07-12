<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Penjualan</p>
                                <h5 class="font-weight-bolder mb-0">
                                    <?php echo e($orders->count()); ?>

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
                                    <?php echo e($products->count()); ?>

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

        <?php if(auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('owner'))): ?>
            <div class="col-xl-6 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Sales</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        Rp. <?php echo e(number_format($totalPenjualan, 0, ',', '.')); ?>

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
        <?php else: ?>
            <div class="col-xl-6 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Kategori</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        <?php echo e($category->count()); ?>

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
        <?php endif; ?>
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
                            <?php if($ordersToday->isEmpty()): ?>
                                <tr>
                                    <td colspan="14" class="text-center">Belum ada penjualan hari ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php $__currentLoopData = $ordersToday; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div>
                                                <a href="/order/<?php echo e($order->id); ?>/edit"
                                                    class="btn btn-secondary btn-sm mb-0 px-2 btn-tooltip"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
                                                    data-container="body" data-animation="true" aria-pressed="true">
                                                    <i class="fas fa-edit text-xs" aria-hidden="true"></i>
                                                </a>
                                            </div>

                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-sm <?php echo e($order->status == 'Pending' ? 'bg-gradient-warning' : ($order->status == 'Diproses' ? 'bg-gradient-primary' : ($order->status == 'Dikirim' || $order->status == 'Diambil' ? 'bg-gradient-success' : 'bg-gradient-secondary'))); ?>"><?php echo e($order->status); ?></span>
                                        </td>
                                        <td>
                                            <p class="mb-0 text-sm"><?php echo e($order->no_faktur); ?></p>
                                        </td>
                                        <td>
                                            <p class="mb-0 text-sm"><?php echo e($order->nama_pembeli); ?></p>
                                        </td>
                                        <td class="align-middle">
                                            <p class="mb-0 text-sm"><?php echo e(Str::limit($order->alamat, 50)); ?></p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-sm"><?php echo e($order->no_hp); ?></span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-gradient-secondary" data-bs-toggle="modal"
                                                data-bs-target="#modal-detail-<?php echo e($order->id); ?>"
                                                style="cursor: pointer">Lihat Detail Order</span>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0"><?php echo e($order->order_via); ?></p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0"><?php echo e($order->tgl_order); ?></p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0"><?php echo e($order->tgl_kirim); ?></p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0"><?php echo e($order->total_qty); ?></p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">Rp
                                                <?php echo e(number_format($order->total_harga_jual, 0, ',', '.')); ?></p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0"><?php echo e($order->title); ?></p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">
                                                <?php if($order->background): ?>
                                                    <a href="<?php echo e(url($order->background)); ?>"
                                                        target="_blank"><?php echo e($order->background); ?></a>
                                                <?php endif; ?>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0"><?php echo e($order->request); ?></p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0"><?php echo e($order->keterangan); ?></p>
                                        </td>

                                    </tr>
                                    
                                    <div class="modal fade" id="modal-detail-<?php echo e($order->id); ?>" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Detail Order
                                                        <?php echo e($order->no_faktur); ?></h5>
                                                    <button type="button" class="btn-close text-dark"
                                                        data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <dl>
                                                        <?php $__currentLoopData = $order->detailOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detailOrder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <dt>Nama Item : <?php echo e(optional($detailOrder->produk)->nama); ?></dt>
                                                            <dd>Qty : <?php echo e($detailOrder->qty); ?>

                                                                <?php echo e(optional($detailOrder->produk)->satuan); ?></dd>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </dl>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn bg-gradient-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://code.highcharts.com/highcharts.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = <?php echo json_encode($hasilApriori); ?>;

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/shatomdc/public_html/sales.shatomedia.com/pengelola-order/resources/views/dashboard/index.blade.php ENDPATH**/ ?>