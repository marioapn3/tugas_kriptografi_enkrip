<?php

use App\Http\Controllers\Transactions\Purchase\PurchaseController;


Route::prefix('transaction')->name('transaction.')->group(function () {
    Route::prefix('purchase')->name('purchase.')->group(function () {
        Route::controller(PurchaseController::class)->group(function () {
            Route::get('/', 'purchaseIndex')->name('index');
            Route::get('/get-data', 'getData')->name('getdata');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/get-data', 'getData')->name('getdata');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update-data/{id}', 'updateData')->name('update');
            Route::delete('/delete-data/{id}', 'deleteData')->name('delete');
        });
    });
});
