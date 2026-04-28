<?php

// START EDIT

use App\Http\Controllers\AdminExportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/browse', [FrontController::class, 'browse'])->middleware('throttle:60,1')->name('front.browse');
Route::get('/search', [FrontController::class, 'search'])->middleware('throttle:60,1')->name('front.search');
Route::get('/category/{category:slug}', [FrontController::class, 'category'])->name('front.category');
Route::get('/details/{house:slug}', [FrontController::class, 'details'])->name('front.details');

Route::match(['get', 'post'], '/mortgage/interest/payment/midtrans/notification', [DashboardController::class, 'paymentMidtransNotification'])->name('front.payment_midtrans_notification');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard/mortgage/{mortgageRequest}/installment/payment', [DashboardController::class, 'installment_payment'])->name('dashboard.installment.payment');
    Route::post('/dashboard/mortgage/installment/payment', [DashboardController::class, 'paymentStoreMidtrans'])->name('dashboard.installment.payment_store_midtrans');
    Route::get('/dashboard/mortgages', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/mortgage/{mortgageRequest}', [DashboardController::class, 'details'])->name('dashboard.mortgage.details');
    Route::get('/dashboard/mortgage/installment/{installment}', [DashboardController::class, 'installment_details'])->name('dashboard.installment.details');
});

Route::middleware(['auth', 'role:admin|master'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/export/stats/excel', [AdminExportController::class, 'excel'])->name('export.stats.excel');
    Route::get('/export/stats/pdf',   [AdminExportController::class, 'pdf'])->name('export.stats.pdf');
});

require __DIR__.'/auth.php';
require __DIR__ . '/agent.php';
require __DIR__ . '/investor.php';
