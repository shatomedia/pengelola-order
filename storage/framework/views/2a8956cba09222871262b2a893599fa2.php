<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body p-3">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(url('proses-apriori')); ?>" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date-input" class="form-control-label">Date</label>
                                    <select name="date" id="date-input" class="form-control select2" style="width: 100%" required>
                                        <option value="">Pilih Tahun</option>
                                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($year); ?>" <?php echo e(request('date') == $year ? 'selected' : ''); ?>><?php echo e($year); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="supportInput" class="form-control-label">Min Support</label>
                                    <input class="form-control" name="min_support" type="number" min="1" id="supportInput" value="<?php echo e(request('min_support') ? request('min_support') : null); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="supportConfidence" class="form-control-label">Min Confidence</label>
                                    <input class="form-control" name="min_confidence" type="number" min="1" id="supportConfidence" value="<?php echo e(request('min_confidence') ? request('min_confidence') : null); ?>" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn bg-gradient-warning w-100 mt-3">Proses</button>
                    </form>

                    <div class="card mb-4 shadow-none">
                        <div class="card-header p-0">
                            <div class="card-body px-0 pt-0 pb-2">
                                <hr>
                                <div class="alert alert-light text-center" style="padding: 5px">1 ITEMSET</div>
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Transaksi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hasil Support</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(count($satuSetItem) > 0): ?>
                                            <?php $__currentLoopData = $satuSetItem; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satuItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="align-middle">
                                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($satuItem['product_name']); ?></p>
                                                    </td>
                                                    <td class="align-middle">
                                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($satuItem['total_transaksi']); ?></p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($satuItem['persentase']); ?> %</p>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center font-italic">Frequent Item Tidak Terbentuk</td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                
                                <hr>
                                <div class="alert alert-light text-center" style="padding: 5px">2 ITEMSET</div>
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Transaksi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hasil Support</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(count($filtered2NameCombinations) > 0): ?>
                                            <?php $__currentLoopData = $filtered2NameCombinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $filteredNameCombination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="align-middle">
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            <?php $__currentLoopData = $filtered2Names[$key]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uniqueName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php echo e($uniqueName); ?>

                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </p>
                                                    </td>
                                                    <td class="align-middle">
                                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($total2YesPerIndex[$key]); ?></p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($persentase2SetItems[$key]); ?> %</p>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center font-italic">Frequent Item Tidak Terbentuk</td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                
                                <hr>
                                <div class="alert alert-light text-center" style="padding: 5px">3 ITEMSET</div>
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Transaksi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hasil Support</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(count($filtered3NameCombinations) > 0): ?>
                                            <?php $__currentLoopData = $filtered3NameCombinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $filteredNameCombination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="align-middle">
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            <?php $__currentLoopData = $filtered3Names[$key]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uniqueName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php echo e($uniqueName); ?>

                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </p>
                                                    </td>
                                                    <td class="align-middle">
                                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($total3YesPerIndex[$key]); ?></p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($persentase3SetItems[$key]); ?> %</p>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center font-italic">Frequent Item Tidak Terbentuk</td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                
                                <hr>
                                <div class="alert alert-light text-center" style="padding: 5px">4 ITEMSET</div>
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Transaksi</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hasil Support</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(count($filtered4NameCombinations) > 0): ?>
                                            <?php $__currentLoopData = $filtered4NameCombinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $filteredNameCombination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="align-middle">
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            <?php $__currentLoopData = $filtered4Names[$key]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uniqueName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php echo e($uniqueName); ?>

                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </p>
                                                    </td>
                                                    <td class="align-middle">
                                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($total4YesPerIndex[$key]); ?></p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0"><?php echo e($persentase4SetItems[$key]); ?> %</p>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center font-italic">Frequent Item Tidak Terbentuk</td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                
                                <hr>
                                <div class="alert alert-success text-center text-light fw-bold" style="padding: 5px">CONFIDENCE</div>
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Produk</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hasil Support</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Confidence</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $tableConfidenceItemSets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $confidenceItemSet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        <?php echo e($confidenceItemSet['nama_product']); ?>

                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0"><?php echo e($confidenceItemSet['persentase_hasil_support']); ?> %</p>
                                                </td>
                                                <td class="align-middle">
                                                    <p class="text-xs font-weight-bold mb-0"><?php echo e($confidenceItemSet['persentase_hasil_confidence']); ?> %</p>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/shatomdc/public_html/sales.shatomedia.com/pengelola-order/resources/views/apriories/index.blade.php ENDPATH**/ ?>