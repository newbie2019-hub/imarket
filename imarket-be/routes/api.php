<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->group(function () {
    Route::controller(AuthenticationController::class)->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/register', 'store');
            Route::post('/login', 'login');
            Route::post('/logout', 'logout');
            Route::post('update-password', 'updatePassword');
            Route::post('me', 'me');
        });
    });

    Route::apiResource('categories', CategoryController::class);
    Route::get('market/latest-products', [MarketController::class, 'latestProducts']);
    Route::apiResource('market', MarketController::class);
    Route::get('cart/count-items', [CartController::class, 'cartCount']);
    Route::delete('cart/removeItem/{id}', [CartController::class, 'destroyCartItem']);
    Route::apiResource('cart', CartController::class);
});
