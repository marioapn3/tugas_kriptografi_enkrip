<?php

use App\Http\Controllers\Report\GeneralLedgerReportController;

Route::prefix('report')->name('report.')->group(function () {
    Route::prefix('ledger')->name('ledger.')->group(function () {
        Route::controller(GeneralLedgerReportController::class)->group(function () {
            Route::get('/', 'ledgerIndex')->name('index');
            Route::get('/get-data', 'getData')->name('getdata');
            Route::get('report/export-excel', 'exportExcelReport')->name('exportexcel');
            Route::get('report/export-pdf', 'exportPdfReport')->name('exportpdf');
        });
    });
});
