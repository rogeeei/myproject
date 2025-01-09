<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ParentTypeController;
use App\Http\Controllers\ProductTypeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Public API
Route::post('/login', [AuthController::class, 'login']);
Route::post('/vendor/login', [AuthController::class, 'vendorLogin']);
Route::post('/vendor', [VendorController::class, 'store'])->name('vendor.store');

//Private API
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::controller(UserController::class)->group(function () {
        Route::get('/user/{id}',                'show');
        Route::get('/user-details',              'getUserDetails');
        Route::put('/user/{id}',                'update');
        Route::delete('/user/{id}',             'destroy');
    });

     Route::controller(StoreController::class)->group(function () {
        Route::get('/store',                     'index');
        Route::get('/store/{id}',                'show');
        Route::post('/store',              'store');
        Route::put('/store/{id}',                'update');
        Route::delete('/store/{id}',             'destroy');
    });

     Route::controller(BrandController::class)->group(function () {
        Route::post('/brand',              'store');
    });
      Route::controller(ParentTypeController::class)->group(function () {
        Route::get('/parent_type',                     'index');
        Route::post('/parent_type',              'store');
        Route::get('/store/{storeId}/category', 'showByCategory');
    });
    Route::controller(ProductTypeController::class)->group(function () {
        Route::post('/product_type',              'store');
    });
     Route::controller(ProductController::class)->group(function () {
       Route::get('/products',                     'index');
       Route::get('/product/{id}',                'show');
        Route::post('/product',              'store');
        Route::put('/product/{id}',                'update');
        Route::delete('/product/{id}',             'destroy');
        Route::get('/store/{storeId}/products', 'showByStore');
    });
 Route::controller(OrderController::class)->group(function () {
        Route::get('/order',                     'index');
        Route::get('/order/{id}',                'show');
        Route::post('/order',              'store');
        Route::put('/order/{id}',                'update');
        Route::delete('/order/{id}',             'destroy');
    });
     Route::controller(CashierController::class)->group(function () {
        Route::get('/cashier',                     'index');
        Route::get('/cashier/{id}',                'show');
        Route::post('/cashier',              'store');
        Route::put('/cashier/{id}',                'update');
        Route::delete('/cashier/{id}',             'destroy');
    });
     Route::controller(CustomerController::class)->group(function () {
        Route::get('/customer',                     'index');
        Route::get('/customer/{id}',                'show');
        Route::post('/customer',              'store');
        Route::put('/customer/{id}',                'update');
        Route::delete('/customer/{id}',             'destroy');
    });
});
