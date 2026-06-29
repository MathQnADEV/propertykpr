<?php

use App\Http\Controllers\Agent\AgentController;
use App\Http\Controllers\Agent\AgentProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AgentProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [AgentProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AgentProfileController::class, 'updatePassword'])->name('profile.password');
    Route::patch('/profile/social', [AgentProfileController::class, 'updateSocial'])->name('profile.social');
    Route::patch("/agent/profile/photo", [AgentProfileController::class, "updatePhoto"])->name("profile.photo");
    Route::get('/listings', [AgentController::class, 'listings'])->name('listings');
    Route::get('/listings/create', [AgentController::class, 'createListing'])->name('listings.create');
    Route::post('/listings', [AgentController::class, 'storeListing'])->name('listings.store');
    Route::get('/listings/{house}', [AgentController::class, 'showListing'])->name('listings.show');
    Route::get('/listings/{house}/edit', [AgentController::class, 'editListing'])->name('listings.edit');
    Route::put('/listings/{house}', [AgentController::class, 'updateListing'])->name('listings.update');
    Route::delete('/listings/{house}', [AgentController::class, 'deleteListing'])->name('listings.delete');
    Route::get('/payments', [AgentController::class, 'paymentRequests'])->name('payments');
    Route::get('/payments/create', [AgentController::class, 'createMortgageRequest'])->name('payments.create');
    Route::post('/payments/create', [AgentController::class, 'storeMortgageRequest'])->name('payments.store');
    Route::get('/payments/{mortgageRequest}', [AgentController::class, 'showPaymentRequest'])->name('payments.show');
    Route::post('/payments/submit', [AgentController::class, 'submitPaymentRequest'])->name('payments.submit');
    Route::get('/documents', [AgentController::class, 'documents'])->name('documents');
    Route::post('/documents/{mortgageRequest}/upload', [AgentController::class, 'uploadDocument'])->name('documents.upload');
    Route::delete('/documents/file/{document}', [AgentController::class, 'deleteDocument'])->name('documents.delete');
    Route::get('/documents/file/{document}/download', [AgentController::class, 'downloadDocument'])->name('documents.download');
    Route::get('/deals', [AgentController::class, 'deals'])->name('deals');
    Route::get('/deals/{mortgageRequest}', [AgentController::class, 'dealDetails'])->name('deals.show');
    Route::get('/reports', [AgentController::class, 'reports'])->name('reports');
    Route::get('/commissions', [AgentController::class, 'commissions'])->name('commissions');
    Route::get('/commissions/request', [AgentController::class, 'requestCommission'])->name('commissions.request');
    Route::post('/commissions/request', [AgentController::class, 'storeCommissionRequest'])->name('commissions.request.store');
    Route::get('/income', fn() => redirect()->route('agent.commissions', ['tab' => 'income']))->name('income');
    Route::get('/notifications', [AgentController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-all-read', [AgentController::class, 'markAllNotificationsRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{notification}/mark-read', [AgentController::class, 'markNotificationRead'])->name('notifications.markRead');
    Route::delete('/notifications/{notification}', [AgentController::class, 'deleteNotification'])->name('notifications.delete');
    Route::post('/notifications/delete-all', [AgentController::class, 'deleteAllNotifications'])->name('notifications.deleteAll');
    Route::get('/activity-log', [AgentController::class, 'activityLog'])->name('activity-log');
    Route::get("/property-browse", [AgentController::class, "propertyBrowse"])->name("property-browse");
});
