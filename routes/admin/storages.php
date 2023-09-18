<?php

use App\Http\Controllers\Storages\ProductController;

Route::prefix('storages')->name('storages.')->group(function () {
    Route::controller(ProductController::class)->middleware('can:view_storage_product')->prefix('product')->name('product.')->group(function () {
        Route::get('/', 'productIndex')->name('index');
        Route::get('/get-data', 'getData')->name('getdata');
    });
});
