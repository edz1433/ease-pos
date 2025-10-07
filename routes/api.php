<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\SalesController;
use App\Http\Controllers\Api\AuthCheckController;

// Public routes (no authentication needed)
// Route::middleware('web')->group(function () {
//     Route::get('/auth/check', [AuthCheckController::class, 'checkAuth']);
//     Route::get('/auth/status', [AuthCheckController::class, 'authStatus']);
// });

// Protected routes - use both web and auth.api middleware
// Route::middleware(['auth.api'])->group(function () {
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/categories', [CategoryController::class, 'categories'])->name('categories');
//     Route::get('/products/{category?}', [ProductController::class, 'products'])->name('products');
    
//     Route::get('/next-transaction-number', [SalesController::class, 'nextTransactionNumber']);
    
//     // Sales routes
//     Route::get('/sales/{date?}', [SalesController::class, 'getSales'])->name('getSales');
//     Route::get('/edit-sales/{saleId}', [SalesController::class, 'editSales'])->name('editSales');
//     Route::put('/update-sales/{saleId}', [SalesController::class, 'updateSales'])->name('updateSales');
// });