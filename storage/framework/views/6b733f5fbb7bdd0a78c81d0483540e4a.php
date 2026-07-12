<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-8">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6><?php echo e($title); ?></h6>
                </div>
                <div class="card-body px-3 pt-0 pb-2">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>

                    <?php endif; ?>
                    <form action="<?php echo e(route('order.update', $order->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="form-group">
                            <label for="inputStatus">Status</label>
                            <select name="status" class="form-control select2" id="inputStatus" required>
                                <option value="">Pilih</option>
                                <option value="Pending" <?php echo e($order->status == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                <option value="Diproses" <?php echo e($order->status == 'Diproses' ? 'selected' : ''); ?>>Diproses</option>
                                <option value="Dikirim" <?php echo e($order->status == 'Dikirim' ? 'selected' : ''); ?>>Dikirim</option>
                                <option value="Batal" <?php echo e($order->status == 'Batal' ? 'selected' : ''); ?>>Batal</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputNamaPembeli">Nama Pembeli</label>
                            <input name="nama_pembeli" type="text" class="form-control" id="inputNamaPembeli" aria-describedby="inputNamaPembeli" placeholder="Nama Pembeli"  value="<?php echo e($order->nama_pembeli); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="inputNoHp">No HP</label>
                            <input name="no_hp" type="number" class="form-control" id="inputNoHp" aria-describedby="inputNoHp" placeholder="No Hp" value="<?php echo e($order->no_hp); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control" placeholder="Alamat Lengkap" required><?php echo e($order->alamat); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="inputOrderVia">Order Via</label>
                            <input name="order_via" type="text" class="form-control" id="inputOrderVia" aria-describedby="inputOrderVia" value="<?php echo e($order->order_via); ?>" placeholder="Order Via" required>
                        </div>
                        <div class="form-group">
                            <label for="inputTglOrder">Tgl Order</label>
                            <input name="tgl_order" type="text" class="form-control datepicker" id="inputTglOrder" aria-describedby="inputTglOrder"value="<?php echo e($order->tgl_order); ?>" placeholder="Tgl Order" required>
                        </div>
                        <div class="form-group">
                            <label for="inputTglKirim">Tgl Kirim</label>
                            <input name="tgl_kirim" type="text" class="form-control datepicker" id="inputTglKirim" aria-describedby="inputTglKirim" value="<?php echo e($order->tgl_kirim); ?>" placeholder="Tgl Kirim" required>
                        </div>
                        <div class="form-group">
                            <label for="inputTitle">Title</label>
                            <input name="title" type="text" class="form-control" id="inputTitle" aria-describedby="inputTitle" placeholder="Title" value="<?php echo e($order->title); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="inputBackground">Background</label>
                            <input name="background" type="text" class="form-control" id="inputBackground" aria-describedby="inputBackground" value="<?php echo e($order->background); ?>" placeholder="Background" required>
                        </div>
                        <div class="form-group">
                            <label for="inputRequest">Request</label>
                            <input name="request" type="text" class="form-control" id="inputRequest" aria-describedby="inputRequest" placeholder="Request" value="<?php echo e($order->request); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="inputKeterangan">Keterangan</label>
                            <input name="keterangan" type="text" class="form-control" id="inputKeterangan" aria-describedby="inputKeterangan" placeholder="Keterangan" value="<?php echo e($order->keterangan); ?>" required>
                        </div>

                        <button type="submit" class="btn bg-gradient-warning">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Detail Produk</h6>
                </div>
                <div class="card-body px-3 pt-0 pb-2 table-responsive">
                    <table class="table table-bordered" style="width: 100%">
                        <thead>
                        <tr>
                            <th>Nama Item</th>
                            <th>Sub Qty</th>
                            <th>Sub Total</th>
                            <th>Opsi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $order->detailOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detailOrder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e(optional($detailOrder->produk)->nama); ?></td>
                                <td><?php echo e($detailOrder->qty); ?> <?php echo e(optional($detailOrder->produk)->satuan); ?></td>
                                <td>Rp <?php echo e(number_format($detailOrder->sub_total,0,',','.')); ?></td>
                                <td>
                                    <form action="<?php echo e(route('detail-order.delete', $detailOrder->id)); ?>" method="post">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
                                        <button type="submit" class="btn btn-sm btn-danger btn-delete-confirm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Tambah Baru</h6>
                </div>
                <form action="<?php echo e(route('detail-order.store', $order->id)); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
                        <div class="form-group">
                            <label for="select-list-products" class="col-form-label">Pilih Produk</label>
                            <select name="produk_id" id="select-list-products" class="form-control" data-placeholder="Pilih" style="width: 100%" required></select>
                        </div>
                        <div class="form-group">
                            <label for="qty" class="col-form-label">Qty</label>
                            <input type="number" name="qty" class="form-control" id="qty" value="<?php echo e(old('qty')); ?>" placeholder="Qty">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn bg-gradient-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('js/product/product-list.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/shatomdc/public_html/sales.shatomedia.com/pengelola-order/resources/views/orders/edit.blade.php ENDPATH**/ ?>