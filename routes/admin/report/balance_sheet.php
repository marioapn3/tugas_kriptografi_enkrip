<?php

use App\Http\Controllers\Report\BalanceReportController;
use App\Http\Controllers\Report\GeneralLedgerReportController;

Route::prefix('report')->name('report.')->group(function () {
    Route::prefix('balance')->name('balance.')->group(function () {
        Route::controller(BalanceReportController::class)->group(function () {
            Route::get('/', 'balanceIndex')->name('index');
            Route::get('/get-data', 'getData')->name('getdata');
            Route::get('report/export-excel', 'exportExcelReport')->name('exportexcel');
            Route::get('report/export-pdf', 'exportPdfReport')->name('exportpdf');
        });
    });
});
