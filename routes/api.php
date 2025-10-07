<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthCheckController,
    CategoryController,
    ProductsControllerApi,
    SalesControllerApi,
    CustomerControllerApi
};

// Public routes (no authentication needed)
// Route::middleware('web')->group(function () {
//     Route::get('/auth/check', [AuthCheckController::class, 'checkAuth']);
//     Route::get('/auth/status', [AuthCheckController::class, 'authStatus']);
// });

// Protected routes - use both web and auth.api middleware
// Route::middleware(['auth.api'])->group(function () {

if (request()->getHost() === '192.168.1.21' || request()->getHost() === 'pos.kerritsolutions.com') {
    config(['session.cookie' => 'ease_pos_cashier_session']);
}

Route::middleware('auth:sanctum')->group(function () {
   Route::get('/auth/check', [AuthCheckController::class, 'checkAuth']);
    Route::get('/auth/status', [AuthCheckController::class, 'authStatus']);

    Route::get('/categories', [CategoryController::class, 'categories'])->name('categories');
    Route::get('/products/{category?}', [ProductsControllerApi::class, 'products'])->name('products');
    Route::get('/all-products', [ProductsControllerApi::class, 'getAllProducts'])->name('getAllProducts');
    Route::get('/products-by-barcode/{barcode}', [ProductsControllerApi::class, 'getProductByBarcode'])->name('getAllProducts');
    Route::post('/checkout', [SalesControllerApi::class, 'checkout']);
    Route::get('/next-transaction-number', [SalesControllerApi::class, 'nextTransactionNumber']);

    //customers
    Route::get('/customers', [CustomerControllerApi::class, 'getCustomers'])->name('getCustomers');

    // Sales routes
    Route::get('/sales/{date?}', [SalesControllerApi::class, 'getSales'])->name('getSales');
    Route::get('/edit-sales/{saleId}', [SalesControllerApi::class, 'editSales'])->name('editSales');
    Route::put('/update-sales/{saleId}', [SalesControllerApi::class, 'updateSales'])->name('updateSales');
    // Cancel an order    // Cancel sale
    Route::post('/sales/{saleId}/cancel-or-return', [SalesControllerApi::class, 'cancelOrReturnSale']);
}); 