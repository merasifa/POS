<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProfileController;
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

Route::pattern('id', '[0-9]+');

// Auth
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postregister']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto']);
// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard / Welcome
    Route::get('/', [WelcomeController::class, 'index']);
    Route::get('/dashboard', [WelcomeController::class, 'dashboard'])->name('dashboard');


    // ADM Only: Level
    Route::middleware(['authorize:ADM'])->prefix('level')->group(function () {
        Route::get('/', [LevelController::class, 'index']);
        Route::post('/list', [LevelController::class, 'list']);
        Route::get('/create', [LevelController::class, 'create']);
        Route::post('/', [LevelController::class, 'store']);
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']);
        Route::post('/ajax', [LevelController::class, 'store_ajax']);
        Route::get('/{id}/edit', [LevelController::class, 'edit']);
        Route::put('/{id}', [LevelController::class, 'update']);
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);
        Route::delete('/{id}', [LevelController::class, 'destroy']);
        Route::get('/import', [LevelController::class, 'import']);
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']);
        Route::get('/level/export_excel', [LevelController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']);
    });

    // ADM & MNG: User, Kategori, Supplier, Barang
    Route::middleware(['authorize:ADM,MNG'])->group(function () {

        // User
        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/list', [UserController::class, 'list']);
            Route::get('/create', [UserController::class, 'create']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('/create_ajax', [UserController::class, 'create_ajax']);
            Route::post('/ajax', [UserController::class, 'store_ajax']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::get('/{id}/edit', [UserController::class, 'edit']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);
            Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
            Route::get('/import', [UserController::class, 'import']);
            Route::post('/import_ajax', [UserController::class, 'import_ajax']);
            Route::get('/user/export_excel', [UserController::class, 'export_excel']); // export excel
            Route::get('/export_pdf', [UserController::class, 'export_pdf']);
        });

        // Kategori
        Route::prefix('kategori')->group(function () {
            Route::get('/', [KategoriController::class, 'index']);
            Route::post('/list', [KategoriController::class, 'list']);
            Route::get('/create', [KategoriController::class, 'create']);
            Route::post('/', [KategoriController::class, 'store']);
            Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
            Route::post('/ajax', [KategoriController::class, 'store_ajax']);
            Route::get('/{id}/edit', [KategoriController::class, 'edit']);
            Route::put('/{id}', [KategoriController::class, 'update']);
            Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']);
            Route::delete('/{id}', [KategoriController::class, 'destroy']);
            Route::get('/import', [KategoriController::class, 'import']);
            Route::post('/import_ajax', [KategoriController::class, 'import_ajax']);
            Route::get('/kategori/export_excel', [KategoriController::class, 'export_excel']); // export excel
            Route::get('/export_pdf', [KategoriController::class, 'export_pdf']);
        });

        // Supplier
        Route::prefix('supplier')->group(function () {
            Route::get('/', [SupplierController::class, 'index']);
            Route::post('/list', [SupplierController::class, 'list']);
            Route::get('/create', [SupplierController::class, 'create']);
            Route::post('/', [SupplierController::class, 'store']);
            Route::get('/create_ajax', [SupplierController::class, 'create_ajax']);
            Route::post('/ajax', [SupplierController::class, 'store_ajax']);
            Route::get('/{id}', [SupplierController::class, 'show']);
            Route::get('/{id}/edit', [SupplierController::class, 'edit']);
            Route::put('/{id}', [SupplierController::class, 'update']);
            Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']);
            Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']);
            Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']);
            Route::delete('/{id}', [SupplierController::class, 'destroy']);
            Route::get('/import', [SupplierController::class, 'import']);
            Route::post('/import_ajax', [SupplierController::class, 'import_ajax']);
            Route::get('/supplier/export_excel', [SupplierController::class, 'export_excel']); // export excel
            Route::get('/export_pdf', [SupplierController::class, 'export_pdf']);
        });

        // Barang
        Route::prefix('barang')->group(function () {
            Route::get('/', [BarangController::class, 'index']);
            Route::post('/list', [BarangController::class, 'list']);
            Route::get('/create', [BarangController::class, 'create']);
            Route::post('/', [BarangController::class, 'store']);
            Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
            Route::post('/ajax', [BarangController::class, 'store_ajax']);
            Route::get('/{id}', [BarangController::class, 'show']);
            Route::get('/{id}/edit', [BarangController::class, 'edit']);
            Route::put('/{id}', [BarangController::class, 'update']);
            Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
            Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']);
            Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);
            Route::delete('/{id}', [BarangController::class, 'destroy']);
            Route::get('/import', [BarangController::class, 'import']);
            Route::post('/import_ajax', [BarangController::class, 'import_ajax']);
            Route::get('/barang/export_excel', [BarangController::class, 'export_excel']); // export excel
            Route::get('/export_pdf', [BarangController::class, 'export_pdf']);
        });

    });

    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        // stok
        Route::prefix('stok')->group(function () {
            Route::get('/', [StokController::class, 'index']);
            Route::post('/list', [StokController::class, 'list']);
            Route::get('/create', [StokController::class, 'create']);
            Route::post('/', [StokController::class, 'store']);
            Route::get('/create_ajax', [StokController::class, 'create_ajax']);
            Route::post('/ajax', [StokController::class, 'store_ajax']);
            Route::get('/{id}', [StokController::class, 'show']);
            Route::get('/{id}/edit', [StokController::class, 'edit']);
            Route::put('/{id}', [StokController::class, 'update']);
            Route::get('/{id}/edit_ajax', [StokController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [StokController::class, 'update_ajax']);
            Route::get('/{id}/show_ajax', [StokController::class, 'show_ajax']);
            Route::get('/{id}/delete_ajax', [StokController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [StokController::class, 'delete_ajax']);
            Route::delete('/{id}', [StokController::class, 'destroy']);
            Route::get('/import', [StokController::class, 'import']);
            Route::post('/import_ajax', [StokController::class, 'import_ajax']);
            Route::get('/stok/export_excel', [StokController::class, 'export_excel']); // export excel
            Route::get('/export_pdf', [StokController::class, 'export_pdf']);
        });

        // penjualan
        Route::prefix('penjualan')->group(function () {
            Route::get('/', [PenjualanController::class, 'index']);
            Route::post('/list', [PenjualanController::class, 'list']);
            Route::get('/create', [PenjualanController::class, 'create']);
            Route::post('/', [PenjualanController::class, 'store']);
            Route::get('/create_ajax', [PenjualanController::class, 'create_ajax']);
            Route::post('/ajax', [PenjualanController::class, 'store_ajax']);
            Route::get('/{id}', [PenjualanController::class, 'show']);
            Route::get('/{id}/edit', [PenjualanController::class, 'edit']);
            Route::put('/{id}', [PenjualanController::class, 'update']);
            Route::get('/{id}/edit_ajax', [PenjualanController::class, 'edit_ajax']);
            Route::put('/penjualan/{id}/update_ajax', [PenjualanController::class, 'update_ajax']);
            Route::get('/{id}/show_ajax', [PenjualanController::class, 'show_ajax']);
            Route::get('/{id}/delete_ajax', [PenjualanController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [PenjualanController::class, 'delete_ajax']);
            Route::delete('/{id}', [PenjualanController::class, 'destroy']);
            Route::get('/import', [PenjualanController::class, 'import']);
            Route::post('/import_ajax', [PenjualanController::class, 'import_ajax']);
            Route::get('/penjualan/export_excel', [PenjualanController::class, 'export_excel']); // export excel
            Route::get('/export_pdf', [PenjualanController::class, 'export_pdf']);
        });
    
});

});