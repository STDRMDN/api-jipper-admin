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
 * route "/logout"
 * @method "POST"
 */
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');

/**
 * route "/user"
 * @method "GET"
 * Protected by JWT
 */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// API Resources without JWT for GET requests only
Route::get('/dyos/{id}', [App\Http\Controllers\Api\CategoryController::class, 'show']);
Route::get('/dyos', [App\Http\Controllers\Api\DyoController::class, 'index']);
Route::get('/orders/{id}', [App\Http\Controllers\Api\FordersController::class, 'show']);
Route::get('/orders', [App\Http\Controllers\Api\FordersController::class, 'index']);
Route::get('/payments-approval/{id}', [App\Http\Controllers\Api\PaymentApprovalController::class, 'show']);
Route::get('/payments-approval', [App\Http\Controllers\Api\PaymentApprovalController::class, 'index']);
Route::get('/wholesale/{id}', [App\Http\Controllers\Api\WholesaleController::class, 'show']);
Route::get('/wholesale', [App\Http\Controllers\Api\WholesaleController::class, 'index']);
Route::get('/message/{id}', [App\Http\Controllers\Api\MessageController::class, 'show']);
Route::get('/message', [App\Http\Controllers\Api\MessageController::class, 'index']);
Route::get('/checkout/{id}', [App\Http\Controllers\Api\CheckoutController::class, 'show']);
Route::get('/checkout', [App\Http\Controllers\Api\CheckoutController::class, 'index']);
Route::get('/categories', [App\Http\Controllers\Api\CategoryController::class, 'index']);
Route::get('/products', [App\Http\Controllers\Api\ProductController::class, 'index']);
Route::get('/products/category/{categoryId}', [App\Http\Controllers\Api\ProductController::class, 'getByCategory']);
Route::get('/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'show']);

/**
 * Simple route for product (GET only, no JWT required)
 */
Route::get('/product', function () {
    return response(['product' => 'Jipper'], 200);
});

// API Resources with JWT protection for POST, PUT, DELETE requests
Route::middleware('auth:api')->group(function () {
    // dyos routes
    Route::post('/dyos', [App\Http\Controllers\Api\DyoController::class, 'store']);
    Route::put('/dyos/{id}', [App\Http\Controllers\Api\DyoController::class, 'update']);

    // orders routes
    Route::post('/orders', [App\Http\Controllers\Api\FordersController::class, 'store']);
    Route::put('/orders/{id}', [App\Http\Controllers\Api\FordersController::class, 'update']);

    // payments-approval routes
    Route::post('/payments-approval', [App\Http\Controllers\Api\PaymentApprovalController::class, 'store']);

    // wholesale routes
    Route::post('/wholesale', [App\Http\Controllers\Api\WholesaleController::class, 'store']);
    Route::put('/wholesale/{id}', [App\Http\Controllers\Api\WholesaleController::class, 'updateStatus']);

    // message routes
    Route::post('/message', [App\Http\Controllers\Api\MessageController::class, 'store']);

    // checkout routes
    Route::post('/checkout', [App\Http\Controllers\Api\CheckoutController::class, 'store']);
    Route::put('/checkout/{id}', [App\Http\Controllers\Api\CheckoutController::class, 'update']);

    // Category routes
    Route::post('/categories', [App\Http\Controllers\Api\CategoryController::class, 'store']);
    Route::put('/categories/{id}', [App\Http\Controllers\Api\CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [App\Http\Controllers\Api\CategoryController::class, 'destroy']);

    // Product routes
    Route::post('/products', [App\Http\Controllers\Api\ProductController::class, 'store']);
    Route::put('/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'update']);
    Route::delete('/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'destroy']);
});
