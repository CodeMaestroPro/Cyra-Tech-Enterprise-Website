<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DesignSystemController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\HomepageController;
use App\Http\Controllers\Web\InitializationController;
use App\Http\Controllers\Web\LeadershipController;
use App\Http\Controllers\Web\PagePreviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomepageController::class)->name('home');
Route::get('/platform/initialization', InitializationController::class)->name('platform.initialization');

Route::get('/about', [AboutController::class, 'show'])->defaults('slug', 'overview')->name('about');
Route::get('/about/vision-mission', [AboutController::class, 'show'])->defaults('slug', 'vision-mission')->name('about.vision-mission');
Route::get('/about/values', [AboutController::class, 'show'])->defaults('slug', 'values')->name('about.values');
Route::get('/about/history', [AboutController::class, 'show'])->defaults('slug', 'history')->name('about.history');
Route::get('/about/why-choose-us', [AboutController::class, 'show'])->defaults('slug', 'why-choose-us')->name('about.why-choose-us');

Route::get('/leadership', LeadershipController::class)->name('leadership');

$previewPages = [
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
