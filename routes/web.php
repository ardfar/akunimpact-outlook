<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovingAverageController;

Route::get('/', [MovingAverageController::class, 'index'])->name('moving_average.index');