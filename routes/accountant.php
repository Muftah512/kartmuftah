<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accountant\DashboardController;

Route::middleware(['auth', 'active'])->prefix('accountant')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('accountant.dashboard');
});

use App\Http\Controllers\Accountant\TransactionController;
Route::get('/transactions', [TransactionController::class, 'index'])->name('accountant.transactions');
