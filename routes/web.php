<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailOrderController;
use App\Http\Controllers\HasilAprioriController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderImportController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranBerulangController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProsesAprioriController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SelectProductController;
use App\Http\Controllers\TemplateOrderController;
use App\Http\Controllers\TransactionCategoryController;
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

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::prefix('order')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('order.index');
        Route::post('/store', [OrderController::class, 'store'])->name('order.store');
        Route::get('/create', [OrderController::class, 'create'])->name('order.create');
        Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('order.edit');
        Route::get('/{id}/invoice', [OrderController::class, 'invoice'])->name('order.invoice');
        Route::get('/{id}/invoice/thermal', [OrderController::class, 'invoiceThermal'])->name('order.invoice.thermal');
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

    Route::prefix('pemasukan')->group(function () {
        Route::get('/', [PemasukanController::class, 'index'])->name('pemasukan.index');
        Route::post('/store', [PemasukanController::class, 'store'])->name('pemasukan.store');
        Route::get('/create', [PemasukanController::class, 'create'])->name('pemasukan.create');
        Route::get('/{id}/edit', [PemasukanController::class, 'edit'])->name('pemasukan.edit');
        Route::put('/{id}/update', [PemasukanController::class, 'update'])->name('pemasukan.update');
        Route::delete('/{id}/delete', [PemasukanController::class, 'destroy'])->name('pemasukan.destroy');
    });

    Route::prefix('pengeluaran')->group(function () {
        Route::get('/', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
        Route::post('/store', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
        Route::get('/create', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
        Route::get('/{id}/edit', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
        Route::put('/{id}/update', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
        Route::delete('/{id}/delete', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
        Route::put('/{id}/confirm', [PengeluaranController::class, 'confirm'])->name('pengeluaran.confirm');
    });

    Route::prefix('pengeluaran-berulang')->group(function () {
        Route::get('/', [PengeluaranBerulangController::class, 'index'])->name('pengeluaran-berulang.index');
        Route::post('/store', [PengeluaranBerulangController::class, 'store'])->name('pengeluaran-berulang.store');
        Route::get('/create', [PengeluaranBerulangController::class, 'create'])->name('pengeluaran-berulang.create');
        Route::get('/{id}/edit', [PengeluaranBerulangController::class, 'edit'])->name('pengeluaran-berulang.edit');
        Route::put('/{id}/update', [PengeluaranBerulangController::class, 'update'])->name('pengeluaran-berulang.update');
        Route::delete('/{id}/delete', [PengeluaranBerulangController::class, 'destroy'])->name('pengeluaran-berulang.destroy');
        Route::post('/generate', [PengeluaranBerulangController::class, 'generate'])->name('pengeluaran-berulang.generate');
    });

    Route::get('/kategori-keuangan', [TransactionCategoryController::class, 'index']);
    Route::post('/kategori-keuangan', [TransactionCategoryController::class, 'store']);
    Route::get('/kategori-keuangan/create', [TransactionCategoryController::class, 'create']);
    Route::get('/kategori-keuangan/{id}/edit', [TransactionCategoryController::class, 'edit']);
    Route::put('/kategori-keuangan/{id}', [TransactionCategoryController::class, 'update']);
    Route::delete('/kategori-keuangan/{id}', [TransactionCategoryController::class, 'destroy']);

    Route::prefix('laporan-keuangan')->group(function () {
        Route::get('/', [LaporanKeuanganController::class, 'index'])->name('laporan-keuangan.index');
    });

    Route::prefix("permission")->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permission.index');
        Route::post('/store', [PermissionController::class, 'store'])->name('permission.store');
        Route::put('/{id}/update', [PermissionController::class, 'update'])->name('permission.update');
        Route::delete('/{id}/delete', [PermissionController::class, 'destroy'])->name('permission.destroy');
    });

    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('account.index');
        Route::put('/update', [AccountController::class, 'update'])->name('account.update');
    });




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

    Route::get('list-products', [ProductController::class, 'listProducts'])->name('list-products');

    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/{id}', [CustomerController::class, 'show'])->name('customers.show');
    });

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});
