<?php

use App\Http\Controllers\Contacts\CustomerController;
use App\Http\Controllers\Contacts\SupplierController;

Route::prefix('contacts')->name('contacts.')->group(function () {
    Route::controller(CustomerController::class)->middleware('can:view_customer')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/', 'customerIndex')->name('index');
        Route::get('/get-data', 'getData')->name('getdata');
        Route::post('/create-data', 'createData')->name('create');
        Route::put('/update-data/{contact}', 'updateData')->name('update');
        Route::delete('/delete-data/{id}', 'deleteData')->name('delete');
    });

    Route::controller(SupplierController::class)->middleware('can:view_supplier')->prefix('supplier')->name('supplier.')->group(function () {
        Route::get('/', 'supplierIndex')->name('index');
        Route::get('/get-data', 'getData')->name('getdata');
        Route::post('/create-data', 'createData')->name('create');
        Route::put('/update-data/{contact}', 'updateData')->name('update');
        Route::delete('/delete-data/{id}', 'deleteData')->name('delete');
    });
});
