<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ProjectManagementController;
use App\Http\Controllers\Admin\CrmController;
use App\Http\Controllers\Admin\MediaLibraryController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DesignSystemController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\CommunityController;
use App\Http\Controllers\Web\CareersController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\PartnerHubController;
use App\Http\Controllers\Web\CmsPageController;
use App\Http\Controllers\Web\ClientPortalController;
use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\InsightsController;
use App\Http\Controllers\Web\HomepageController;
use App\Http\Controllers\Web\InitializationController;
use App\Http\Controllers\Web\InnovationLabController;
use App\Http\Controllers\Web\IndustriesController;
use App\Http\Controllers\Web\LeadershipController;
use App\Http\Controllers\Web\PortfolioController;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\SolutionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomepageController::class)->name('home');
Route::get('/platform/initialization', InitializationController::class)->name('platform.initialization');

Route::get('/about', [AboutController::class, 'show'])->defaults('slug', 'overview')->name('about');
Route::get('/about/vision-mission', [AboutController::class, 'show'])->defaults('slug', 'vision-mission')->name('about.vision-mission');
Route::get('/about/values', [AboutController::class, 'show'])->defaults('slug', 'values')->name('about.values');
Route::get('/about/history', [AboutController::class, 'show'])->defaults('slug', 'history')->name('about.history');
Route::get('/about/why-choose-us', [AboutController::class, 'show'])->defaults('slug', 'why-choose-us')->name('about.why-choose-us');

Route::get('/leadership', LeadershipController::class)->name('leadership');

Route::get('/solutions', [SolutionsController::class, 'index'])->name('solutions');
Route::get('/solutions/{slug}', [SolutionsController::class, 'show'])->name('solutions.show');

Route::get('/products', [ProductsController::class, 'index'])->name('products');
Route::get('/products/{slug}', [ProductsController::class, 'show'])->name('products.show');

Route::get('/industries', [IndustriesController::class, 'index'])->name('industries');
Route::get('/industries/{slug}', [IndustriesController::class, 'show'])->name('industries.show');

Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])->name('portfolio.show');

Route::get('/innovation-lab', [InnovationLabController::class, 'index'])->name('innovation-lab');
Route::get('/innovation-lab/{slug}', [InnovationLabController::class, 'show'])->name('innovation-lab.show');

Route::get('/community', [CommunityController::class, 'index'])->name('community');
Route::get('/community/{slug}', [CommunityController::class, 'show'])->name('community.show');

Route::get('/insights', [InsightsController::class, 'index'])->name('insights');
Route::get('/insights/{slug}', [InsightsController::class, 'show'])->name('insights.show');

Route::get('/careers', [CareersController::class, 'index'])->name('careers');
Route::get('/careers/{slug}', [CareersController::class, 'show'])->name('careers.show');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');

Route::get('/partner-hub', [PartnerHubController::class, 'index'])->name('partner-hub');
Route::get('/partner-hub/{slug}', [PartnerHubController::class, 'show'])->name('partner-hub.show');

Route::get('/pages/{slug}', [CmsPageController::class, 'show'])->name('pages.show');

Route::get('/client-portal', [ClientPortalController::class, 'index'])->name('client-portal');
Route::middleware(['auth', 'permission:client-portal.access'])->prefix('client-portal')->name('client-portal.')->group(function () {
    Route::get('/dashboard', [ClientPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/engagements/{slug}', [ClientPortalController::class, 'show'])->name('engagements.show');
});

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

        Route::middleware('permission:cms.view')->prefix('cms')->name('cms.')->group(function () {
            Route::get('/', [CmsController::class, 'index'])->name('index');
            Route::get('/create', [CmsController::class, 'create'])
                ->middleware('permission:cms.create')
                ->name('create');
            Route::post('/', [CmsController::class, 'store'])
                ->middleware('permission:cms.create')
                ->name('store');
            Route::get('/{slug}/edit', [CmsController::class, 'edit'])->name('edit');
            Route::put('/{slug}', [CmsController::class, 'update'])
                ->middleware('permission:cms.update')
                ->name('update');
            Route::post('/{slug}/publish', [CmsController::class, 'publish'])
                ->middleware('permission:cms.publish')
                ->name('publish');
            Route::post('/{slug}/unpublish', [CmsController::class, 'unpublish'])
                ->middleware('permission:cms.publish')
                ->name('unpublish');
        });

        Route::middleware('permission:media.view')->prefix('media')->name('media.')->group(function () {
            Route::get('/', [MediaLibraryController::class, 'index'])->name('index');
            Route::post('/', [MediaLibraryController::class, 'store'])
                ->middleware('permission:media.upload')
                ->name('store');
            Route::get('/{uuid}/edit', [MediaLibraryController::class, 'edit'])->name('edit');
            Route::put('/{uuid}', [MediaLibraryController::class, 'update'])
                ->middleware('permission:media.update')
                ->name('update');
            Route::delete('/{uuid}', [MediaLibraryController::class, 'destroy'])
                ->middleware('permission:media.delete')
                ->name('destroy');
        });

        Route::get('/analytics', AnalyticsController::class)
            ->middleware('permission:analytics.view')
            ->name('analytics.index');

        Route::middleware('permission:crm.view')->prefix('crm')->name('crm.')->group(function () {
            Route::get('/', [CrmController::class, 'index'])->name('index');
            Route::get('/create', [CrmController::class, 'create'])
                ->middleware('permission:crm.create')
                ->name('create');
            Route::post('/', [CrmController::class, 'store'])
                ->middleware('permission:crm.create')
                ->name('store');
            Route::get('/{reference}/edit', [CrmController::class, 'edit'])->name('edit');
            Route::put('/{reference}', [CrmController::class, 'update'])
                ->middleware('permission:crm.update')
                ->name('update');
            Route::post('/{reference}/stage', [CrmController::class, 'updateStage'])
                ->middleware('permission:crm.manage')
                ->name('stage');
            Route::post('/inquiries/{inquiry}/convert', [CrmController::class, 'convertInquiry'])
                ->middleware('permission:crm.create')
                ->name('inquiries.convert');
        });

        Route::middleware('permission:projects.view')->prefix('projects')->name('projects.')->group(function () {
            Route::get('/', [ProjectManagementController::class, 'index'])->name('index');
            Route::get('/tasks', [ProjectManagementController::class, 'tasks'])->name('tasks');
            Route::get('/create', [ProjectManagementController::class, 'create'])
                ->middleware('permission:projects.create')
                ->name('create');
            Route::post('/', [ProjectManagementController::class, 'store'])
                ->middleware('permission:projects.create')
                ->name('store');
            Route::get('/{reference}/edit', [ProjectManagementController::class, 'edit'])->name('edit');
            Route::put('/{reference}', [ProjectManagementController::class, 'update'])
                ->middleware('permission:projects.update')
                ->name('update');
            Route::post('/{reference}/progress', [ProjectManagementController::class, 'updateProgress'])
                ->middleware('permission:projects.manage')
                ->name('progress');
            Route::post('/{reference}/tasks', [ProjectManagementController::class, 'storeTask'])
                ->middleware('permission:projects.manage')
                ->name('tasks.store');
            Route::put('/{reference}/tasks/{taskReference}', [ProjectManagementController::class, 'updateTask'])
                ->middleware('permission:projects.manage')
                ->name('tasks.update');
        });
    });
