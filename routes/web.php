<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Transaction;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/add-record', [Transaction::class, 'index'])->name('add-record');
Route::post('/store-record', [Transaction::class, 'store'])->name('store-record');
Route::get('/get-omzet/{range?}', [DashboardController::class, 'get_omzet'])->name('get-omzet');
Route::get('/get-impact-secure/{range?}', [DashboardController::class, 'get_impact_secure'])->name('get-impact-secure');
Route::get('/get-finance-statistic/{range?}/{period?}', [DashboardController::class, 'get_finance_statistic'])->name('get-finance-statistic');

