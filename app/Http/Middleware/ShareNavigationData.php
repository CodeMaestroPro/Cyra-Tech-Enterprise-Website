<?php

namespace App\Http\Middleware;

use App\Services\NavigationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShareNavigationData
{
    public function __construct(
        private readonly NavigationService $navigationService,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->routeIs('login')) {
            view()->share('publicNavigation', $this->navigationService->getPublicNavigation());
            view()->share('publicSearchIndex', $this->navigationService->getPublicSearchIndex());
        }

        if ($request->is('admin*') && auth()->check()) {
            view()->share('adminNavigation', $this->navigationService->getAdminNavigation());
            view()->share('adminSearchIndex', $this->navigationService->getAdminSearchIndex());
        }

        return $next($request);
    }
}
