<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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
    return view('welcome');
});

Route::get('/index', [HomeController::class, 'index']);
Route::prefix('category')->group(function () {
    Route::get('/', [ProductController::class, 'showCategories']); 
    Route::get('{category}', [ProductController::class, 'showCategory']); 
});
Route::get('/user/{id}/name/{name}', [UserController::class, 'profile']); 
Route::get('/sales', [PenjualanController::class, 'sales']); 
