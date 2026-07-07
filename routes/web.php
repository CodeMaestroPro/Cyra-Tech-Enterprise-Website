<?php

use App\Http\Controllers\Web\InitializationController;
use Illuminate\Support\Facades\Route;

Route::get('/', InitializationController::class)->name('home');
