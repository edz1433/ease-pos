<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CashierController;

Route::get('/', function () {
    return view('login');
});

//login
Route::get('/',[LoginAuthController::class,'getLogin'])->name('getLogin');
Route::post('/login',[LoginAuthController::class,'postLogin'])->name('postLogin');

// Authenticated routes
Route::group(['middleware' => ['login_auth']], function() {
    Route::get('dashboard', [MasterController::class, 'dashboard'])->name('dashboard');
    
    Route::prefix('account')->group(function() {
        Route::get('/',[MasterController::class,'userRead'])->name('userRead');
        Route::post('/', [UserController::class, 'userEdit'])->name('userEdit');
        Route::post('/create',[UserController::class,'userCreate'])->name('userCreate');
        Route::post('/update', [UserController::class, 'userUpdate'])->name('userUpdate');
        Route::post('/delete/{id}', [UserController::class, 'userDelete'])->name('userDelete');

    });

    Route::prefix('products')->group(function() {
        Route::get('/',[MasterController::class,'productRead'])->name('productRead');
        Route::get('/create',[ProductController::class,'productCreate'])->name('productCreate');
        Route::post('/edit',[ProductController::class,'productEdit'])->name('productEdit');
        Route::get('/update',[ProductController::class,'productUpdate'])->name('productUpdate');
        Route::get('/delete',[ProductController::class,'productDelete'])->name('productDelete');

        Route::get('/presets', [ProductController::class, 'getProductPresets'])->name('getProductPresets');
        Route::get('/next-barcode', [ProductController::class, 'getNextBarcode'])->name('getNextBarcode');
    });

    Route::prefix('myaccount')->group(function() {
        Route::get('/',[UserController::class,'userAccount'])->name('userAccount');
        Route::post('/accnt-update',[UserController::class,'userAccntUpdate'])->name('userAccntUpdate');
    });

    Route::prefix('pos')->group(function() {
        Route::get('/', [CashierController::class, 'pos'])->name('pos');
    });
    
    // Additional logout route if needed
    Route::get('/logout', [MasterController::class, 'logout'])->name('logout');
});


