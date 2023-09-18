<?php

use App\Http\Controllers\Storages\ProductController;

Route::prefix('storages')->name('storages.')->group(function () {
    Route::controller(ProductController::class)->middleware('can:view_storage_product')->prefix('product')->name('product.')->group(function () {
        Route::get('/', 'productIndex')->name('index');
        Route::get('/get-data', 'getData')->name('getdata');

        Route::get('/generate-code', 'generateCode')->name('generatecode');
        Route::post('/create-data', 'createData')->name('create');
        Route::post('{id}/update-data', 'updateData')->name('update');
        Route::delete('{id}/delete-data', 'deleteData')->name('delete');

         // put the detailed route which only displays the ID at the bottom
         Route::get('{id}', 'show')->name('show');
    });
});
