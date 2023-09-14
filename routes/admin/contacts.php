<?php

use App\Http\Controllers\Contacts\CustomerController;

Route::prefix('contacts')->name('contacts.')->group(function () {
    Route::controller(CustomerController::class)->prefix('customer')->name('customer.')->group(function () {
        Route::get('/', 'customerIndex')->name('index');
        Route::get('/get-data', 'getData')->name('getdata');
        Route::post('/create-data', 'createData')->name('create');
        Route::put('/update-data/{contact}', 'updateData')->name('update');
        Route::delete('/delete-data/{id}', 'deleteData')->name('delete');
    });
});
