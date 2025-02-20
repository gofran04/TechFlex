<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\DeliveryCostController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\OrderController;


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

Route::group(['middleware' => 'guest'], function (){
    Route::post('register',[AuthController::class, 'register'])->name('register');
    Route::post('login',[AuthController::class, 'login'])->name('login');
    Route::apiResource('products',ProductController::class)->only(['index','show']);
    Route::apiResource('categories',CategoryController::class)->only(['index','show']);
    Route::apiResource('stores',StoreController::class)->only(['index','show']);

});

Route::group(['middleware' => 'auth'],function(){
    Route::apiResource('products',ProductController::class)->only(['store','update','destroy']);//->name('products');
    Route::apiResource('categories',CategoryController::class)->only(['store','update','destroy']);
    Route::apiResource('orders',OrderController::class)->except(['destroy']);
    Route::apiResource('users',UserController::class);
    Route::apiResource('stores',StoreController::class)->only(['update']);
    Route::apiResource('delivery-costs',DeliveryCostController::class);
});