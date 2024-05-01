<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/get-omzet/{range?}', [DashboardController::class, 'get_omzet'])->name('get-omzet');
Route::get('/get-finance-statistic/{range?}/{period?}', [DashboardController::class, 'get_finance_statistic'])->name('get-finance-statistic');

