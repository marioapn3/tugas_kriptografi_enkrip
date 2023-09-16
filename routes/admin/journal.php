<?php

use App\Http\Controllers\Journal\AccountCategoryController;
use App\Http\Controllers\Journal\AccountController;


Route::prefix('journals')->name('journals.')->group(function () {
    Route::controller(AccountCategoryController::class)->prefix('account-categories')->name('account-categories.')->group(function () {
        Route::get('/get-data', 'getData')->name('getdata');
        Route::get('/', 'accountCategoryIndex')->name('index');
        Route::post('/create-data', 'createData')->name('create');
        Route::put('/update-data/{account_category}', 'updateData')->name('update');
        Route::delete('/delete-data/{id}', 'deleteData')->name('delete');
    });

    // Route::controller(SupplierController::class)->middleware('can:view_supplier')->prefix('supplier')->name('supplier.')->group(function () {
    //     Route::get('/', 'supplierIndex')->name('index');
    //     Route::get('/get-data', 'getData')->name('getdata');
    // });
});
