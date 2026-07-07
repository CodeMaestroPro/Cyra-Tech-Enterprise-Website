<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DesignSystemController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\HomepageController;
use App\Http\Controllers\Web\InitializationController;
use App\Http\Controllers\Web\PagePreviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomepageController::class)->name('home');
Route::get('/platform/initialization', InitializationController::class)->name('platform.initialization');

$previewPages = [
    'about',
    'solutions',
    'products',
    'industries',
    'portfolio',
    'innovation-lab',
    'community',
    'insights',
    'careers',
    'contact',
];

foreach ($previewPages as $slug) {
    Route::get("/{$slug}", [PagePreviewController::class, 'show'])
        ->defaults('slug', $slug)
        ->name(str_replace('/', '-', $slug));
}

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'permission:dashboard.access'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/design-system', DesignSystemController::class)
            ->middleware('permission:modules.view')
            ->name('design-system');
    });
