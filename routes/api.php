<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\AuthController;


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

});

Route::apiResource('products',ProductController::class);
Route::apiResource('categories',CategoryController::class);

