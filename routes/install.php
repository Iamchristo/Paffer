<?php

use App\Http\Controllers\InstallController;
use Illuminate\Support\Facades\Route;

Route::prefix('install')->name('install.')->group(function () {
    Route::middleware('guest.install')->group(function () {
        Route::get('/', [InstallController::class, 'welcome'])->name('welcome');
        Route::get('/database', [InstallController::class, 'database'])->name('database');
        Route::post('/database', [InstallController::class, 'storeDatabase'])->name('database.store');
        Route::get('/admin', [InstallController::class, 'admin'])->name('admin');
        Route::post('/admin', [InstallController::class, 'storeAdmin'])->name('admin.store');
        Route::get('/demo-data', [InstallController::class, 'demoData'])->name('demo-data');
        Route::post('/demo-data', [InstallController::class, 'storeDemoData'])->name('demo-data.store');
    });

    // Reachable even after install completes, since storeDemoData() redirects
    // here right after creating the "installed" marker that guest.install checks.
    Route::get('/finish', [InstallController::class, 'finish'])->name('finish');
});
