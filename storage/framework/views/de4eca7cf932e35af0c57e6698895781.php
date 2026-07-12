<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(asset('img/apple-icon.png')); ?>">
    <link rel="icon" type="image/png" href="<?php echo e(asset('img/favicon.png')); ?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/scroller/2.2.0/css/scroller.bootstrap5.min.css" />

    <title>
        Shatomedia | <?php echo e($title); ?>

    </title>
    <?php echo $__env->make('layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-100">
<?php echo $__env->make('sweetalert::alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <?php echo $__env->make('layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <?php if(session()->has('success')): ?>
                <div class="row">
                    <div class="col-12">
                        <div id="alert" class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    </div>
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('alert').style.display = 'none';
                    }, 5000);
                </script>
            <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>

        <footer class="footer pt-3  ">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script> |
                            Shatomedia
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</main>
<!--   Core JS Files   -->
<?php echo $__env->make('layouts.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->yieldContent('scripts'); ?>
</body>

</html>
<?php /**PATH /home/shatomdc/public_html/sales.shatomedia.com/pengelola-order/resources/views/layouts/master.blade.php ENDPATH**/ ?>