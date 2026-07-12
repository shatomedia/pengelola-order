<?php $__env->startSection('content'); ?>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-3">Hasil Perhitungan Apriori</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nama Penguji
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Hasil Support (%)
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Hasil Confidence (%)
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Tanggal
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $hasilAprioris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hasilApriori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="d-flex px-3"><p class="text-xs font-weight-bold mb-0"><?php echo e(optional($hasilApriori->user)->name); ?></p></td>
                                    <td><p class="text-xs font-weight-bold mb-0"><?php echo e($hasilApriori->persentase_hasil_support); ?> &</p></td>
                                    <td><p class="text-xs font-weight-bold mb-0"><?php echo e($hasilApriori->persentase_hasil_confidence); ?> %</p></td>
                                    <td><p class="text-xs font-weight-bold mb-0"><?php echo e(Carbon\Carbon::parse($hasilApriori->tanggal)->isoFormat('DD MMM YYYY')); ?></p></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="pagination justify-content-center">
                    <?php echo e($hasilAprioris->withQueryString()->links()); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/shatomdc/public_html/sales.shatomedia.com/pengelola-order/resources/views/hasilApriori/index.blade.php ENDPATH**/ ?>