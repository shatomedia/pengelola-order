

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>Edit Kategori Produk</h6>
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
        <form action="/product-category/<?php echo e($productcategories->id); ?>" method="POST">
          <?php echo method_field('put'); ?>
          <?php echo csrf_field(); ?>
          <div class="form-group">
            <label for="inputNamaKategori">Nama Kategori</label>
            <input name="nama_kategori" type="text" class="form-control" id="inputNamaKategori" aria-describedby="inputNamaKategori" placeholder="Nama Kategori" value="<?php echo e($productcategories->nama_kategori); ?>">
          </div>
          <div class="form-group">
            <label for="inputDeskripsi">Deskripsi</label>
            <input name="deskripsi" type="text" class="form-control" id="inputDeskripsi" aria-describedby="inputDeskripsi" placeholder="Deskripsi" value="<?php echo e($productcategories->deskripsi); ?>">
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/shatomdc/public_html/sales.shatomedia.com/pengelola-order/resources/views/product_categories/edit.blade.php ENDPATH**/ ?>