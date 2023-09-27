<?php

use App\Http\Controllers\Journal\AccountCategoryController;
use App\Http\Controllers\Journal\AccountController;
use App\Http\Controllers\Journal\JournalController;

Route::prefix('journals')->name('journals.')->group(function () {

    Route::controller(JournalController::class)->middleware('can:view_manual_journal')->prefix('journal')->name('journal.')->group(function () {
        Route::get('/', 'journalIndex')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/get-data', 'getData')->name('getdata');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update-data/{id}', 'updateData')->name('update');
        Route::delete('/delete-data/{id}', 'deleteData')->name('delete');

        Route::get('{id}', 'show')->name('show');
    });

    Route::controller(AccountCategoryController::class)->middleware('can:view_account_category')->prefix('account-categories')->name('account-categories.')->group(function () {
        Route::get('/get-data', 'getData')->name('getdata');
        Route::get('/', 'accountCategoryIndex')->name('index');
        Route::get('/generate-code/{id_classification}', 'generateCode')->name('generatecode');
        Route::post('/create-data', 'createData')->name('create');
        Route::put('/update-data/{account_category}', 'updateData')->name('update');
        Route::delete('/delete-data/{id}', 'deleteData')->name('delete');
    });

    Route::controller(AccountController::class)->middleware('can:view_account')->prefix('accounts')->name('accounts.')->group(function () {
        Route::get('/', 'accountIndex')->name('index');
        Route::get('/get-data', 'getData')->name('getdata');

        Route::get('/generate-code/{account_category_id}', 'generateCode')->name('generatecode');
        Route::post('/create-data', 'createData')->name('create');
        Route::put('/update-data/{account}', 'updateData')->name('update');
        Route::delete('/delete-data/{id}', 'deleteData')->name('delete');
    });
});
