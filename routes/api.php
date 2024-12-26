<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * route "/register"
 * @method "POST"
 */
Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');

/**
 * route "/login"
 * @method "POST"
 */
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');

/**
 * route "/user"
 * @method "GET"
 */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// API Resources
Route::apiResource('/dyos', App\Http\Controllers\Api\DyoController::class);
Route::apiResource('/orders', App\Http\Controllers\Api\FordersController::class);
Route::apiResource('/payment-approval', App\Http\Controllers\Api\PaymentApprovalController::class);
Route::apiResource('/wholesale', App\Http\Controllers\Api\WholesaleController::class);
Route::apiResource('/message', App\Http\Controllers\Api\MessageController::class);
Route::apiResource('/checkout', App\Http\Controllers\Api\CheckoutController::class);

// Update status order
Route::put('/orders/{id}/status', [App\Http\Controllers\Api\CheckoutController::class, 'updateStatus']);
Route::put('/dyos/{id}/status', [App\Http\Controllers\Api\DyoController::class, 'updateStatus']);
Route::put('/forders/{id}/status', [App\Http\Controllers\Api\FordersController::class, 'updateStatus']);
Route::put('/wholesale/{id}/status', [App\Http\Controllers\Api\WholesaleController::class, 'updateStatus']);

// Simple route for product
Route::get('/product', function () {
    return response(['product' => 'Jipper'], 200);
});

/**
 * route "/logout"
 * @method "POST"
 */
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');
