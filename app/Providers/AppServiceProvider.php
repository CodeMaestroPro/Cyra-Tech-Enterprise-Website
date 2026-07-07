<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Repositories\PermissionRepository;
use App\Repositories\PlatformModuleRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\DesignSystemService;
use App\Services\PlatformService;
use App\Services\RoleService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PlatformModuleRepository::class);
        $this->app->singleton(PermissionRepository::class);
        $this->app->singleton(RoleRepository::class);
        $this->app->singleton(UserRepository::class);
        $this->app->singleton(PlatformService::class);
        $this->app->singleton(DesignSystemService::class);
        $this->app->singleton(AuthService::class);
        $this->app->singleton(RoleService::class);
    }

    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::define('manage-roles', [RolePolicy::class, 'manage']);
        Gate::define('view-roles', [RolePolicy::class, 'viewAny']);
    }
}
