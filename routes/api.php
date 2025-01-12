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
Route::apiResource('/checkout', App\Http\Controllers\Api\CheckoutController::class);
Route::apiResource('/dyos', App\Http\Controllers\Api\DyoController::class);
Route::apiResource('/orders', App\Http\Controllers\Api\FordersController::class);
Route::apiResource('/wholesale', App\Http\Controllers\Api\WholesaleController::class);

// Simple route for product
Route::get('/product', function () {
    return response(['product' => 'Jipper'], 200);
});

/**
 * route "/logout"
 * @method "POST"
 */
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');

// Tambahkan route untuk category dan product

// Category routes
Route::post('/categories', [App\Http\Controllers\Api\CategoryController::class, 'store']);
Route::put('/categories/{id}', [App\Http\Controllers\Api\CategoryController::class, 'update']);
Route::delete('/categories/{id}', [App\Http\Controllers\Api\CategoryController::class, 'destroy']);
Route::get('/categories', [App\Http\Controllers\Api\CategoryController::class, 'index']);

// Product routes
Route::post('/products', [App\Http\Controllers\Api\ProductController::class, 'store']);
Route::get('/products', [App\Http\Controllers\Api\ProductController::class, 'index']);
Route::get('/products/category/{categoryId}', [App\Http\Controllers\Api\ProductController::class, 'getByCategory']);
Route::get('/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'show']);
Route::put('/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'update']);
Route::delete('/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'destroy']);
