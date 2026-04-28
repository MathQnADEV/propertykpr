<?php

use App\Http\Controllers\Investor\InvestorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:investor'])->prefix('investor')->name('investor.')->group(function () {

    Route::get('/dashboard', [InvestorController::class, 'dashboard'])->name('dashboard');

    Route::get('/export/excel', [InvestorController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf',   [InvestorController::class, 'exportPdf'])->name('export.pdf');
});
