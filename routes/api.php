<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DesignSystemController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\PlatformController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class)->name('api.health');
Route::get('/platform/status', [PlatformController::class, 'status'])->name('api.platform.status');
Route::get('/platform/modules', [PlatformController::class, 'modules'])->name('api.platform.modules');

Route::get('/design-system/tokens', [DesignSystemController::class, 'tokens'])->name('api.design-system.tokens');
Route::get('/design-system/catalog', [DesignSystemController::class, 'catalog'])->name('api.design-system.catalog');

Route::middleware(['web', 'auth'])->prefix('auth')->name('api.auth.')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::get('/permissions', [AuthController::class, 'permissions'])->name('permissions');
    Route::get('/roles', [AuthController::class, 'roles'])
        ->middleware('permission:roles.view')
        ->name('roles');
});
