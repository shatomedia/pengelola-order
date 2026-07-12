<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(asset('img/apple-icon.png')); ?>">
    <link rel="icon" type="image/png" href="<?php echo e(asset('img/favicon.png')); ?>">
    <title>Dashboard</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="<?php echo e(asset('css/nucleo-icons.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('css/nucleo-svg.css')); ?>" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="<?php echo e(asset('css/nucleo-svg.css')); ?>" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="<?php echo e(asset('css/soft-ui-dashboard.css?v=1.0.7')); ?>" rel="stylesheet" />


<body class="">
<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">

        </div>
    </div>
</div>
<main class="main-content  mt-0">
    <section>
        <div class="page-header min-vh-75">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                        <div class="card card-plain mt-8">
                            <div class="card-header pb-0 text-left bg-transparent">
                                <h3 class="text-center font-weight-bolder text-warning text-gradient">Welcome back</h3>
                                <p class="text-center mb-0">Masukkan Email dan Password Anda!</p>
                            </div>
                            <div class="card-body">
                                <form role="form" action="<?php echo e(route('login.store')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <label>Email</label>
                                    <div class="mb-3">
                                        <input type="email" name="email" required class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                                    </div>
                                    <label>Password</label>
                                    <div class="mb-3">
                                        <input type="password" name="password" required class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-warning w-100 mt-4 mb-0">Sign in</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>


<!--   Core JS Files   -->
<script src="<?php echo e(asset('js/core/popper.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/core/bootstrap.min.js')); ?>"></script>


<!-- Github buttons -->

<!-- Control Center parallax effects, scripts for the example pages etc -->
<script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
</body>

</html>
<?php /**PATH /home/shatomdc/public_html/sales.shatomedia.com/pengelola-order/resources/views/auth/login.blade.php ENDPATH**/ ?>