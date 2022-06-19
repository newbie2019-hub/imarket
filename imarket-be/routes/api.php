<?php

use App\Http\Controllers\AdminArchivedCategoryController;
use App\Http\Controllers\AdminArchivedProductController;
use App\Http\Controllers\AdminArchivedStoreController;
use App\Http\Controllers\AdminArchivedUserController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminStoreController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PartnershipController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreInfoController;
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
            Route::post('/update-address', 'updateAddress');
            Route::post('/register', 'store');
            Route::get('/logs', 'userLogs');
            Route::post('/login', 'login');
            Route::post('/logout', 'logout');
            Route::post('update', 'update');
            Route::post('update-password', 'updatePassword');
            Route::post('me', 'me');
            Route::apiResource('partnership', PartnershipController::class);
        });

        Route::post('update-profile', 'updateProfileImg');
        Route::post('remove-profile', 'deleteProfileImg');
    });    

    Route::prefix('admin')->group(function () {
        Route::apiResource('archived-users', AdminArchivedUserController::class);
        Route::apiResource('users', AdminUserController::class);
        
        Route::apiResource('dashboard', AdminDashboardController::class);
        Route::apiResource('archived-category', AdminArchivedCategoryController::class);
        Route::apiResource('category', AdminCategoryController::class);
        
        Route::apiResource('archived-stores', AdminArchivedStoreController::class);
        Route::put('store/{store}', [AdminStoreController::class, 'approveStore']);
        Route::apiResource('stores', AdminStoreController::class);
        
        Route::apiResource('archived-products', AdminArchivedProductController::class);
        Route::apiResource('products', AdminProductController::class);
    });

    Route::apiResource('categories', CategoryController::class);
    
    Route::get('market/search', [MarketController::class, 'searchProduct']);
    Route::get('market/latest-products', [MarketController::class, 'latestProducts']);
    Route::apiResource('market', MarketController::class);
    
    Route::put('cart/{cart}/product/{product}', [CartController::class, 'updateProductQuantity']);
    Route::get('cart/count-items', [CartController::class, 'cartCount']);
    Route::delete('cart/removeItem/{id}', [CartController::class, 'destroyCartItem']);
    Route::apiResource('cart', CartController::class);
    
    Route::apiResource('checkout', OrderController::class);
    
    Route::post('products/uploadImage', [ProductController::class, 'uploadImage']);
    Route::apiResource('products', ProductController::class);

    Route::apiResource('orders', OrderController::class);

    Route::post('store/update-banner',[StoreInfoController::class, 'updateBannerImg']);
    Route::apiResource('store', StoreInfoController::class);
});
