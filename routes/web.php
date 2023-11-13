<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailOrderController;
use App\Http\Controllers\HasilAprioriController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderImportController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProsesAprioriController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SelectProductController;
use App\Http\Controllers\TemplateOrderController;
use App\Http\Controllers\UserManageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return to_route('login.index');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login.index');
    Route::post('/login/store', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return redirect('/dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::prefix('order')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('order.index');
        Route::post('/store', [OrderController::class, 'store'])->name('order.store');
        Route::get('/create', [OrderController::class, 'create'])->name('order.create');
        Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('order.edit');
        Route::put('/{id}/update', [OrderController::class, 'update'])->name('order.update');
        Route::delete('/{id}/delete', [OrderController::class, 'destroy'])->name('order.destroy');
    });

    Route::prefix('detail-order')->group(function () {
        Route::post('/store/{id}', [DetailOrderController::class, 'store'])->name('detail-order.store');
        Route::delete('/{id}/delete', [DetailOrderController::class, 'delete'])->name('detail-order.delete');
    });

    Route::post('/order-import', [OrderImportController::class, 'store']);

    Route::get('/product', [ProductController::class, 'index']);
    Route::post('/product', [ProductController::class, 'store']);
    Route::get('/product/create', [ProductController::class, 'create']);
    Route::get('/product/{id}/edit', [ProductController::class, 'edit']);
    Route::put('/product/{id}', [ProductController::class, 'update']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);

    Route::get('/product-category', [ProductCategoryController::class, 'index']);
    Route::post('/product-category', [ProductCategoryController::class, 'store']);
    Route::get('/product-category/create', [ProductCategoryController::class, 'create']);
    Route::get('/product-category/{id}/edit', [ProductCategoryController::class, 'edit']);
    Route::put('/product-category/{id}', [ProductCategoryController::class, 'update']);
    Route::delete('/product-category/{id}', [ProductCategoryController::class, 'destroy']);
    Route::get('/select-product', [SelectProductController::class, 'index'])->name('select-product');

    Route::get('/proses-apriori', [ProsesAprioriController::class, 'index']);

    Route::prefix('laporan-penjualan')->group(function () {
        Route::get('/', [LaporanPenjualanController::class, 'index'])->name('laporan-penjualan.index');
        Route::get('/export', [LaporanPenjualanController::class, 'export'])->name('laporan-penjualan.export');
    });

    Route::get('/hasil-apriori', [HasilAprioriController::class, 'index']);

    Route::prefix("permission")->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permission.index');
        Route::post('/store', [PermissionController::class, 'store'])->name('permission.store');
        Route::put('/{id}/update', [PermissionController::class, 'update'])->name('permission.update');
        Route::delete('/{id}/delete', [PermissionController::class, 'destroy'])->name('permission.destroy');
    });

    Route::get('/account', [AccountController::class, 'index']);


    Route::prefix('users')->group(function () {
        Route::get('/', [UserManageController::class, 'index'])->name('users.index');
        Route::post('/store', [UserManageController::class, 'store'])->name('users.store');
        Route::put('/{id}/update', [UserManageController::class, 'update'])->name('users.update');
        Route::delete('/{id}/delete', [UserManageController::class, 'destroy'])->name('users.destroy');
    });

    Route::prefix('roles')->group(function () {
        Route::put('/{id}/update', [RoleController::class, 'update'])->name('roles.update');
    });

    Route::get('/template-penjualan', [TemplateOrderController::class, 'export']);

    Route::get('/logout', [AuthController::class, 'logout']);
});
