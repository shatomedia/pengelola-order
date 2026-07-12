

<?php $__env->startSection('content'); ?>
<div class="col-12">
  <div class="card mb-4">
    <div class="card-header pb-0">
      <h6>Data Kategori Produk</h6>
      <div class="text-end">
        <a class="btn bg-gradient-warning mb-4" href="/product-category/create"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah</a>
      </div>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
      <div class="table-responsive p-0">
        <table class="table align-items-center justify-content-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Kategori</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Deskripsi</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $productcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td>
                <div class="d-flex px-3">
                    <h6 class="mb-0 text-sm"><?php echo e($productcategory->nama_kategori); ?></h6>
                </div>
              </td>
              
              <td>
                <span class="text-xs font-weight-bold"><?php echo e($productcategory->deskripsi); ?></span>
              </td>
              
                <td class="align-middle text-center">
                  <div class="d-flex align-items-center justify-content-center">
                    <a class="btn btn-link text-dark px-3 mb-0" href="/product-category/<?php echo e($productcategory->id); ?>/edit"><i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Edit</a>
                    <form action="/product-category/<?php echo e($productcategory->id); ?>" method="POST">
                      <?php echo method_field('DELETE'); ?>
                      <?php echo csrf_field(); ?>
                      <button type="submit" class="btn btn-link text-danger text-gradient px-3 mb-0">
                          <i class="far fa-trash-alt me-2" aria-hidden="true"></i>Delete
                      </button>
                  </form>
                  
                  </div>
                  
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/shatomdc/public_html/sales.shatomedia.com/pengelola-order/resources/views/product_categories/index.blade.php ENDPATH**/ ?>