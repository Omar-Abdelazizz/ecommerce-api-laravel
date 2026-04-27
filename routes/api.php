<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\VariantController;
use App\Http\Controllers\Api\ItemVariantController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {

    Route::apiResource('categories', CategoryController::class)
        ->except(['index', 'show'])
        ->middleware('permission:manage categories');

    Route::apiResource('items', ItemController::class)
        ->except(['index', 'show'])
        ->middleware('permission:manage items');

    Route::apiResource('variants', VariantController::class)
        ->except(['index', 'show'])
        ->middleware('permission:manage variants');

    Route::apiResource('discounts', DiscountController::class)
        ->except(['index', 'show'])
        ->middleware('permission:manage discounts');

    Route::post('item-variants', [ItemVariantController::class, 'store'])
        ->middleware('permission:manage items');
});

Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show']);

Route::get('items', [ItemController::class, 'index']);
Route::get('items/{item}', [ItemController::class, 'show']);

Route::get('variants', [VariantController::class, 'index']);
Route::get('variants/{variant}', [VariantController::class, 'show']);

Route::get('discounts', [DiscountController::class, 'index']);
Route::get('discounts/{discount}', [DiscountController::class, 'show']);

Route::get('item-variants', [ItemVariantController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('show');
        Route::post('/', [CartController::class, 'add'])->name('add');
        Route::patch('/items/{item_variant_id}', [CartController::class, 'update'])->name('update');
        Route::delete('/items/{item_variant_id}', [CartController::class, 'delete'])->name('delete');
        Route::delete('/{cart_id}', [CartController::class, 'remove'])->name('remove');
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    });

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/me', fn(Request $request) => $request->user());
