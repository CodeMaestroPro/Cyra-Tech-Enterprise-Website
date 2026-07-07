<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Repositories\AboutPageRepository;
use App\Repositories\HomepageSectionRepository;
use App\Repositories\LeadershipProfileRepository;
use App\Repositories\NavigationItemRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\PlatformModuleRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Services\AboutService;
use App\Services\AuthService;
use App\Services\DesignSystemService;
use App\Services\HomepageService;
use App\Services\LeadershipService;
use App\Services\NavigationService;
use App\Services\PlatformService;
use App\Services\RoleService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PlatformModuleRepository::class);
        $this->app->singleton(NavigationItemRepository::class);
        $this->app->singleton(HomepageSectionRepository::class);
        $this->app->singleton(AboutPageRepository::class);
        $this->app->singleton(LeadershipProfileRepository::class);
        $this->app->singleton(PermissionRepository::class);
        $this->app->singleton(RoleRepository::class);
        $this->app->singleton(UserRepository::class);
        $this->app->singleton(PlatformService::class);
        $this->app->singleton(NavigationService::class);
        $this->app->singleton(HomepageService::class);
        $this->app->singleton(AboutService::class);
        $this->app->singleton(LeadershipService::class);
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
