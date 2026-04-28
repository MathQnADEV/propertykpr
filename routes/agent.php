<?php

use App\Http\Controllers\Agent\AgentController;
use App\Http\Controllers\Agent\AgentProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [AgentProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [AgentProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AgentProfileController::class, 'updatePassword'])->name('profile.password');

    // Listings
    Route::get('/listings', [AgentController::class, 'listings'])->name('listings');
    Route::get('/listings/create', [AgentController::class, 'createListing'])->name('listings.create');
    Route::post('/listings', [AgentController::class, 'storeListing'])->name('listings.store');
    Route::get('/listings/{house}/edit', [AgentController::class, 'editListing'])->name('listings.edit');
    Route::put('/listings/{house}', [AgentController::class, 'updateListing'])->name('listings.update');
    Route::delete('/listings/{house}', [AgentController::class, 'deleteListing'])->name('listings.delete');

    // Payment Requests
    Route::get('/payments', [AgentController::class, 'paymentRequests'])->name('payments');
    Route::get('/payments/create', [AgentController::class, 'createMortgageRequest'])->name('payments.create');
    Route::post('/payments/create', [AgentController::class, 'storeMortgageRequest'])->name('payments.store');
    Route::get('/payments/{mortgageRequest}', [AgentController::class, 'showPaymentRequest'])->name('payments.show');
    Route::post('/payments/submit', [AgentController::class, 'submitPaymentRequest'])->name('payments.submit');

    // Upload Proof & Documents
    Route::get('/documents', [AgentController::class, 'documents'])->name('documents');
    Route::post('/documents/{mortgageRequest}/upload', [AgentController::class, 'uploadDocument'])->name('documents.upload');

    // Deals (Sold / In Process / Failed)
    Route::get('/deals', [AgentController::class, 'deals'])->name('deals');
    Route::get('/deals/{mortgageRequest}', [AgentController::class, 'dealDetails'])->name('deals.show');

    // Reports
    Route::get('/reports', [AgentController::class, 'reports'])->name('reports');
});
