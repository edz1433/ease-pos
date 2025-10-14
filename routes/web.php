<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsClassController;
use App\Http\Controllers\ProductBundleController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CashBankController;
use App\Http\Controllers\CashCountController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchasesController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\Api\AuthCheckController;
use App\Http\Controllers\Api\ProductsControllerApi;
use App\Http\Controllers\Api\CustomerControllerApi;
use App\Http\Controllers\Api\SalesControllerApi;

Route::get('/', function () {
    return view('login');
});

//login
Route::get('/',[LoginAuthController::class,'getLogin'])->name('getLogin');
Route::post('/login',[LoginAuthController::class,'postLogin'])->name('postLogin');
Route::get('/logout', [MasterController::class, 'logout'])->name('logout');

Route::prefix('api')->group(function () {
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

// Authenticated routes
Route::group(['middleware' => ['login_auth']], function() {
    Route::get('dashboard', [MasterController::class, 'dashboard'])->name('dashboard');
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // JSON endpoint for AJAX
    Route::get('/dashboard/data', [DashboardController::class, 'dashboardData'])->name('dashboardData');

    Route::prefix('purchases')->group(function () {
        Route::get('/', [MasterController::class, 'purchaseRead'])->name('purchaseRead'); 
        Route::get('/form', [PurchasesController::class, 'purchaseForm'])->name('purchaseForm'); 
        Route::post('/create', [PurchasesController::class, 'purchaseCreate'])->name('purchaseCreate'); 

        Route::post('/store-item', [PurchasesController::class, 'purchaseStoreItem'])->name('purchaseStoreItem');
        Route::get('/purchase-cancel/{id}', [PurchasesController::class, 'purchaseCancel'])->name('purchasesCancel');
        Route::post('/purchase-save/{id}', [PurchasesController::class, 'purchasesSave'])->name('purchasesSave');
        
    });

    Route::prefix('warehouse')->group(function() {
        Route::get('/',[MasterController::class,'warehouseRead'])->name('warehouseRead');
    });

    Route::prefix('products')->group(function() {
        Route::get('/',[MasterController::class,'productRead'])->name('productRead');
        Route::get('/products/ajax', [ProductController::class, 'productReadAjax'])->name('products.ajax');
        Route::post('/create',[ProductController::class,'productCreate'])->name('productCreate');
        Route::match(['post', 'put'], '/store-or-update', [ProductController::class, 'storeOrUpdate'])->name('storeOrUpdate');
        Route::put('/update/{id}',[ProductController::class,'productUpdate'])->name('productUpdate');

        Route::get('/presets', [ProductController::class, 'getProductPresets'])->name('getProductPresets');
        Route::get('/next-barcode/{id}', [ProductController::class, 'getNextBarcode'])->name('getNextBarcode');

        Route::prefix('classifications')->group(function() { 
            Route::get('/', [ProductsClassController::class, 'classificationRead'])->name('classificationRead');
            
            Route::prefix('categories')->group(function() { 
                Route::post('/save', [ProductsClassController::class, 'categoriesSave'])->name('categoriesSave');
                Route::get('/delete/{id}', [ProductsClassController::class, 'categoriesDelete'])->name('categoriesDelete');
            });
        });

        Route::prefix('stock-adjustment')->group(function() { 
            Route::post('/stock-adjustment-create', [StockAdjustmentController::class, 'stockAdjustmentCreate'])->name('stockAdjustmentCreate');
        });
        

        Route::prefix('units')->group(function() { 
            Route::post('/save', [ProductsClassController::class, 'unitSave'])->name('unitSave');
            Route::get('/delete/{id}', [ProductsClassController::class, 'unitsDelete'])->name('unitsDelete');
        });
        
        Route::prefix('bundles')->group(function() {
            Route::get('/{id?}', [ProductBundleController::class, 'bundleRead'])->name('bundleRead');
            Route::post('/create', [ProductBundleController::class, 'bundleCreate'])->name('bundleCreate');
            Route::post('/update/{id}', [ProductBundleController::class, 'bundleUpdate'])->name('bundleUpdate');
            Route::post('/edit', [ProductBundleController::class, 'bundleEdit'])->name('bundleEdit');
            Route::post('/add-item', [ProductBundleController::class, 'bundleAddItem'])->name('bundleAddItem');
            Route::get('/remove-item/{id}', [ProductBundleController::class, 'bundleRemoveItem'])->name('bundleRemoveItem');
        });
    });

    Route::prefix('sales')->group(function() {
        Route::get('/',[MasterController::class,'salesRead'])->name('salesRead');
        Route::post('/filter', [SalesController::class, 'salesFilter'])->name('salesFilter');
    });

    Route::prefix('inventory')->group(function() {
        Route::get('/',[MasterController::class,'inventoryRead'])->name('inventoryRead');
        Route::get('/form/{id}',[InventoryController::class,'inventoryForm'])->name('inventoryForm');

        Route::post('/start',[InventoryController::class,'inventoryStart'])->name('inventoryStart');
        Route::post('/inventories-items-save/{id}', [InventoryController::class, 'inventoriesItemSave'])->name('inventoriesItemSave');

        Route::post('/inventories-save-finalize', [InventoryController::class, 'finalizeInventory'])->name('finalizeInventory');

        Route::post('/create',[InventoryController::class,'inventoryCreate'])->name('inventoryCreate');
        Route::post('/',[InventoryController::class,'inventoryEdit'])->name('inventoryEdit');
        Route::post('/update/{id}',[InventoryController::class,'inventoryUpdate'])->name('inventoryUpdate');
    });

    Route::prefix('suppliers')->group(function () {
        Route::get('/', [MasterController::class, 'supplierRead'])->name('supplierRead'); 
        Route::post('/create', [SupplierController::class, 'supplierCreate'])->name('supplierCreate');
        Route::post('/', [SupplierController::class, 'supplierEdit'])->name('supplierEdit');
        Route::post('/update/{id}', [SupplierController::class, 'supplierUpdate'])->name('supplierUpdate');
    });

    Route::prefix('cash-bank')->middleware(['auth'])->group(function () {
        Route::get('/{date?}', [MasterController::class, 'cashbankRead'])->name('cashbankRead');
        Route::post('/create', [CashBankController::class, 'cashbankCreate'])->name('cashbankCreate');
        Route::get('/edit/{id}', [CashBankController::class, 'cashbankEdit'])->name('cashbankEdit');
        Route::post('/update/{id}', [CashBankController::class, 'cashbankUpdate'])->name('cashbankUpdate');
        Route::delete('/delete/{id}', [CashBankController::class, 'cashbankDelete'])->name('cashbankDelete');
    });

    Route::prefix('cash-count')->group(function () {
        Route::get('/', [MasterController::class, 'cashCountRead'])->name('cashCountRead'); 
        Route::get('/cash-entry/{id?}', [CashCountController::class, 'cashCountEntry'])->name('cashCountEntry'); 
        Route::post('/cash-count-create', [CashCountController::class, 'cashCountCreate'])->name('cashCountCreate'); 
    });

    Route::prefix('customers')->group(function() {
        Route::get('/',[MasterController::class,'customerRead'])->name('customerRead');
        Route::post('/ceate', [CustomerController::class, 'customerCreate'])->name('customerCreate');
        Route::post('/update/{id}', [CustomerController::class, 'customerUpdate'])->name('customerUpdate');

        Route::post('/customers-payment', [CustomerController::class, 'customerPayment'])->name('customerPayment');
        Route::get('/customers-payment-history/{customerId}', [CustomerController::class, 'customerPaymentsHistory'])->name('customerPaymentsHistory');
    });

    Route::prefix('user')->group(function() {
        Route::get('/',[MasterController::class,'userRead'])->name('userRead');
        Route::post('/', [UserController::class, 'userEdit'])->name('userEdit');
        Route::post('/create',[UserController::class,'userCreate'])->name('userCreate');
        Route::post('/update/{id}', [UserController::class, 'userUpdate'])->name('userUpdate');
    });

    Route::prefix('myaccount')->group(function() {
        Route::get('/',[UserController::class,'userAccount'])->name('userAccount');
        Route::post('/accnt-update',[UserController::class,'userAccntUpdate'])->name('userAccntUpdate');
    });

    Route::prefix('pos')->group(function() {
        Route::get('/', [CashierController::class, 'pos'])->name('pos');
    });

    Route::prefix('delete')->group(function() {
        Route::post('/', [MasterController::class, 'delete'])->name('delete');
    });  
    
    // Additional logout route if needed
});


