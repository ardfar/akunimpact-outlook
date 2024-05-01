<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\AnalystController;

use Illuminate\Support\Facades\Route;

Route::get('/', [AnalystController::class, 'showAnalyst'])->name('analyst');