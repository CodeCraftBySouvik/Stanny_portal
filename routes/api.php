<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\FabricController;
use App\Http\Controllers\Api\BusinessTypeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TodoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/country-list', [AuthController::class, 'CountryList']);
Route::get('/country/details/{id}', [AuthController::class, 'CountryDetailsByID']);
Route::get('/business-type', [BusinessTypeController::class, 'index']);
Route::post('/check-device', [AuthController::class, 'checkDevice']);
Route::post('/user-login', [AuthController::class, 'userLogin']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/set-mpin', [AuthController::class, 'setMpin']);
Route::post('/mpin-login', [AuthController::class, 'mpinLogin']);

Route::post('/forgot-mpin', [AuthController::class, 'forgotMpin']);
Route::post('/verify-otp-mpin', [AuthController::class, 'verifyOtpMpin']);
Route::post('/reset-mpin', [AuthController::class, 'resetMpin']);


// Route::middleware('auth:sanctum', 'token.expiry')->group(function () {
Route::middleware('auth:sanctum', 'token.session')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
        
    // profile
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/dashboard', [AuthController::class, 'dashboard']);
    Route::get('/customer/list', [AuthController::class, 'customer_list']);
    Route::get('/customer/details/{id}', [AuthController::class, 'customer_details']);
    Route::get('/customer/filter', [AuthController::class, 'customer_filter']);
    Route::post('/customer/store', [AuthController::class, 'customer_store']);
    Route::post('/customer/update/{id}', [AuthController::class, 'customer_update']);
    Route::get('/customer/order/list', [OrderController::class, 'customer_order_list']);
    Route::get('/customer/order/detail', [OrderController::class, 'customer_order_detail']);




    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/list', [UserController::class, 'list'])->name('user.list');
        Route::get('/search', [UserController::class, 'search'])->name('user.search');
        Route::get('/show/{id}', [UserController::class, 'show'])->name('user.show');
    });

    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/category-collection-wise/{categoryid}', [CategoryController::class, 'getCategoriesByCollection'])->name('category.collection-wise');
    });
    
    Route::get('/collection', [CollectionController::class, 'index']);
    
    
   
    Route::prefix('product')->group(function () {
        Route::get('/products-category-collection-wise', [ProductController::class, 'getProductsByCategoryAndCollection']);
        Route::get('/products-collection-wise', [ProductController::class, 'getProductsByCollection']);
        Route::get('/fabric/{id}', [FabricController::class, 'index']);
        Route::get('/check/price', [ProductController::class, 'checkPrice']);
        Route::get('/measurement-product-wise', [ProductController::class, 'getMeasurementProductwise']);
        
    });
    
    Route::prefix('catalogue')->group(function () {
        Route::get('/list', [ProductController::class, 'catalogueList']);
        Route::get('/pages', [ProductController::class, 'pages']);
        Route::get('/page/item', [ProductController::class, 'pageItem']);
        Route::get('/products-collection-wise', [ProductController::class, 'getProductsByCollection']);
       
        
    });
    
    
    Route::prefix('order')->group(function () { 
        // Route::post('/store', [OrderController::class, 'createOrder']);
        Route::post('/store', [OrderController::class, 'store']);
        Route::post('/video/store', [OrderController::class, 'createVideo']);
        Route::get('/list', [OrderController::class, 'index']);
        Route::get('/detail', [OrderController::class, 'detail']);
        Route::get('/ledger-view', [OrderController::class, 'ledgerView']);
        Route::post('/payment-receipt-save', [OrderController::class, 'paymentReceiptSave']);
        Route::post('/skip-order', [OrderController::class, 'skipOrderBill']);
        Route::post('/cashbook-module', [OrderController::class, 'cashbookModule']);
    });

    Route::prefix('todo')->group(function () { 
        Route::get('/list', [TodoController::class, 'todoList']);
    });
    
    Route::prefix('notes')->group(function () { 
        Route::post('/store', [TodoController::class, 'noteStore']);
    });
    
   
});