<?php

use App\Http\Controllers\Transactions\Selling\PosController;
use App\Http\Controllers\Transactions\Selling\SaleController;

Route::prefix('transaction')->name('transaction.')->group(function () {
    Route::controller(PosController::class)->prefix('pos')->name('pos.')->group(function () {
        Route::get('/', 'posIndex')->name('index');
        Route::get('get-product', 'getProduct')->name('getproduct');
    });

    Route::controller(SaleController::class)->prefix('sale')->name('sale.')->group(function () {
        Route::get('/', 'saleIndex')->name('index');
        Route::get('get-data', 'getData')->name('getdata');
        Route::get('/create', 'create')->name('create');
        Route::get('/get-product/{id}', 'getProduct')->name('getproduct');
        Route::post('/store', 'storeData')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'updateData')->name('update');
        Route::delete('/delete/{id}', 'deleteData')->name('delete');

        Route::get('{id}', 'show')->name('show');
    });
});
