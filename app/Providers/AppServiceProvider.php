<?php

namespace App\Providers;

use App\Repositories\PlatformModuleRepository;
use App\Services\PlatformService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PlatformModuleRepository::class);
        $this->app->singleton(PlatformService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
