<?php

use App\Http\Controllers\Report\IncomeStatementController;

Route::prefix('report')->name('report.')->group(function () {
    Route::prefix('income-statement')->name('income-statement.')->group(function () {
        Route::controller(IncomeStatementController::class)->group(function () {
            Route::get('/', 'incomeIndex')->name('index');
            Route::get('/get-data', 'getData')->name('getdata');
            Route::get('report/export-excel', 'exportExcelReport')->name('exportexcel');
            Route::get('report/export-pdf', 'exportPdfReport')->name('exportpdf');
        });
    });
});
