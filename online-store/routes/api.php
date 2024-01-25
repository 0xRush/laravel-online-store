<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', UserController::class.'@register');
Route::post('/login', UserController::class.'@login');
Route::get('/logout', UserController::class.'@logout')->middleware('auth:sanctum');

Route::get('/products', ProductController::class.'@getProducts')->middleware('auth:sanctum');
Route::get('/product/{id}', ProductController::class.'@getProduct')->middleware('auth:sanctum');
Route::post('/createProduct', ProductController::class.'@createProduct')->middleware('auth:sanctum');
Route::put('/updateProduct/{id}', ProductController::class.'@updateProduct')->middleware('auth:sanctum');
Route::delete('/deleteProduct/{id}', ProductController::class.'@deleteProduct')->middleware('auth:sanctum');

Route::post('/product/{id}/addToCart', OrderController::class.'@addToCart')->middleware('auth:sanctum');
Route::get('/cart', OrderController::class.'@showCart')->middleware('auth:sanctum');
Route::get('/cartHistory', OrderController::class.'@showCartHistory')->middleware('auth:sanctum');
Route::post('/pay', OrderController::class.'@pay')->middleware('auth:sanctum');


