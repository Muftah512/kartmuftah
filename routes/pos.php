<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pos\DashboardController;

Route::middleware(['auth', 'active'])->prefix('pos')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pos.dashboard');
});

use App\Http\Controllers\Pos\SalesController;
Route::get('/sales-report', [SalesController::class, 'index'])->name('pos.sales.report');
