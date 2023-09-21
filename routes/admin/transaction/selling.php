<?php

use App\Http\Controllers\Transactions\Selling\PosController;

Route::prefix('transaction')->name('transaction.')->group(function () {
    Route::prefix('selling')->name('selling.')->group(function () {
        Route::controller(PosController::class)->prefix('pos')->name('pos.')->group(function () {
            Route::get('/', 'posIndex')->name('index');
        });
    });
});
