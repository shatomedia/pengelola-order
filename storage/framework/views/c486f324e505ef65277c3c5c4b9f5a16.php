<?php $__env->startSection('content'); ?>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-3">Laporan Penjualan</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('laporan-penjualan.index')); ?>" method="get">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date">Pilih Rentang Tanggal Order</label>
                                        <input type="text" class="form-control daterange" name="date" id="date" value="<?php echo e(!$date ? '' : $date); ?>" placeholder="Rentang Tanggal" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="select-list-products">Pilih Produk</label>
                                        <select name="product_id" id="select-list-products" class="form-control select2" data-placeholder="Pilih" style="width: 100%">
                                            <option value="<?php echo e(request('product_id') ? request('product_id') : ''); ?>"><?php echo e(request('product_id') ? $product->nama : ''); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="select-list-status">Pilih Status</label>
                                        <select name="status" id="select-list-status" class="form-control select2" style="width: 100%">
                                            <option value="">Semua</option>
                                            <?php $__currentLoopData = $listStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($status->status); ?>" <?php echo e(request('status') == $status->status ? 'selected' : ''); ?>><?php echo e($status->status); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <div class="float-end">
                                <button type="submit" name="export" value="true" class="btn btn-outline-dark">Export</button>
                            </div>
                        </form>
                    </div>
                    <hr>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Nama Pembeli</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Alamat</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">No HP</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total Produk</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Order Via</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Tgl Order</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Tgl Kirim</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total Qty</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Total Harga Jual
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Title</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Background</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Request</th>
                                        <th class="text-uppercase text-secondary text-xxs opacity-7 ps-2">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-sm <?php echo e($order->status == 'Pending' ? 'bg-gradient-warning' : ($order->status == 'Diproses' ? 'bg-gradient-primary' : ($order->status == 'Dikirim' ? 'bg-gradient-success' : ($order->status == 'Diambil' ? 'bg-gradient-secondary' : 'bg-gradient-danger')))); ?>"><?php echo e($order->status); ?></span>
                                            </td>
                                            <td>
                                                <p class="mb-0 text-sm"><?php echo e($order->nama_pembeli); ?></p>
                                            </td>
                                            <td class="align-middle">
                                                <p class="mb-0 text-sm"><?php echo e(Str::limit($order->alamat, 50)); ?></p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <p class="text-sm mb-0"><?php echo e($order->no_hp); ?></p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <p class="text-sm mb-0"><?php echo e($order->detail_orders_count); ?> Item</p>
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
                                                <p class="text-sm mb-0"><?php echo e($order->background); ?></p>
                                            </td>
                                            <td>
                                                <p class="text-sm mb-0"><?php echo e($order->request); ?></p>
                                            </td>
                                            <td>
                                                <p class="text-sm mb-0"><?php echo e($order->keterangan); ?></p>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer pagination float-end">
                        <?php echo e($orders->withQueryString()->links()); ?>

                    </div>
                </div>
                <?php if($product): ?>
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-3">Laporan Penjualan <?php echo e($product->nama); ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                            <tr>
                                <th>Tgl Order</th>
                                <th>Jumlah Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $monthList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($month); ?></td>
                                    <td><?php echo e($jumlahQty[$key]); ?> Qty</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('js/product/product-list.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/shatomdc/public_html/sales.shatomedia.com/pengelola-order/resources/views/laporanPenjualan/index.blade.php ENDPATH**/ ?>