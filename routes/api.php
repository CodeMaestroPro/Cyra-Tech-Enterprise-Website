<?php

use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DesignSystemController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\HomepageController;
use App\Http\Controllers\Api\LeadershipController;
use App\Http\Controllers\Api\NavigationController;
use App\Http\Controllers\Api\SolutionsController;
use App\Http\Controllers\Api\PlatformController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class)->name('api.health');
Route::get('/platform/status', [PlatformController::class, 'status'])->name('api.platform.status');
Route::get('/platform/modules', [PlatformController::class, 'modules'])->name('api.platform.modules');

Route::get('/homepage', [HomepageController::class, 'show'])->name('api.homepage.show');

Route::get('/about', [AboutController::class, 'index'])->name('api.about.index');
Route::get('/about/{slug}', [AboutController::class, 'show'])->name('api.about.show');

Route::get('/leadership', [LeadershipController::class, 'index'])->name('api.leadership.index');
Route::get('/leadership/{slug}', [LeadershipController::class, 'show'])->name('api.leadership.show');

Route::get('/solutions', [SolutionsController::class, 'index'])->name('api.solutions.index');
Route::get('/solutions/{slug}', [SolutionsController::class, 'show'])->name('api.solutions.show');

Route::get('/design-system/tokens', [DesignSystemController::class, 'tokens'])->name('api.design-system.tokens');
Route::get('/design-system/catalog', [DesignSystemController::class, 'catalog'])->name('api.design-system.catalog');

Route::get('/navigation/public', [NavigationController::class, 'publicNavigation'])->name('api.navigation.public');
Route::get('/navigation/admin', [NavigationController::class, 'adminNavigation'])
    ->middleware(['web', 'auth', 'permission:dashboard.access'])
    ->name('api.navigation.admin');

Route::middleware(['web', 'auth'])->prefix('auth')->name('api.auth.')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::get('/permissions', [AuthController::class, 'permissions'])->name('permissions');
    Route::get('/roles', [AuthController::class, 'roles'])
        ->middleware('permission:roles.view')
        ->name('roles');
});
