<?php

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

Route::middleware(['auth:api'])->group(function () {
    Route::post('orders/createOrder', [\App\Http\Controllers\Api\OrderController::class, 'createOrder'])->name('create_order');
    Route::post('order/{order_id}/extendRentalOrder', [\App\Http\Controllers\Api\OrderController::class, 'extendRentalOrder'])->name('extend_rental_order');
    Route::get('order/{order_id}', [\App\Http\Controllers\Api\OrderController::class, 'getOrder'])->name('get_order');
    Route::get('orders/getOrderHistory', [\App\Http\Controllers\Api\OrderController::class, 'getOrderHistory'])->name('get_order_history');
});
