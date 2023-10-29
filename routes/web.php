<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HasilAprioriController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderImportController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProsesAprioriController;
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

Route::middleware('guest')->group(function() {
    Route::get('login', [AuthController::class, 'index'])->name('login.index');
    Route::post('/login/store', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function() {
    Route::get('/home', function() {
        return redirect('/dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::prefix('order')->group(function (){
        Route::get('/', [OrderController::class, 'index'])->name('order.index');
        Route::post('/order', [OrderController::class, 'store'])->name('order.store');
        Route::get('/create', [OrderController::class, 'create'])->name('order.create');
        Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('order.edit');
        Route::put('/{id}/update', [OrderController::class, 'update'])->name('order.update');
        Route::delete('/{id}/delete', [OrderController::class, 'destroy'])->name('order.destroy');
        Route::post('/{id}/add-order-product', [OrderController::class, 'newProduct'])->name('order.new-product');
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

    Route::get('/laporan-penjualan', [LaporanPenjualanController::class, 'index']);

    Route::get('/hasil-apriori', [HasilAprioriController::class, 'index']);

    Route::get('/account', [AccountController::class, 'index']);
    Route::get('/users', [UserManageController::class, 'index']);

    Route::get('/template-penjualan', [TemplateOrderController::class, 'export']);

    Route::get('/logout', [AuthController::class, 'logout']);
});



