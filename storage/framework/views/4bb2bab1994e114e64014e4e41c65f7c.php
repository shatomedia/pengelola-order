<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Data Penjualan</h6>
                    <div class="d-flex mb-2 float-end">
                        <a class="btn bg-gradient-warning " href="/order/create"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah</a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-5">
                            <form action="<?php echo e(url('/template-penjualan')); ?>" method="GET">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-outline-info mb-0">
                                    <i class="fas fa-download"></i> Unduh Template Excel
                                </button>
                            </form>
                        </div>
                        <div class="col-7">
                            <form action="<?php echo e(url('/order-import')); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="input-group">
                                    <input name="template" type="file" required class="form-control"
                                        placeholder="Upload File" aria-label="Upload" aria-describedby="button-addon4">
                                    <button class="btn bg-gradient-info mb-0" type="submit">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
                                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                                    <a href="<?php echo e(url($order->background)); ?>" target="_blank"><?php echo e($order->background); ?></a>
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
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer pagination float-end">
                        <?php echo e($orders->withQueryString()->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/shatomdc/public_html/sales.shatomedia.com/pengelola-order/resources/views/orders/index.blade.php ENDPATH**/ ?>