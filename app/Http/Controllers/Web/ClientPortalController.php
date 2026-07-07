<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ClientPortalService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientPortalController extends Controller
{
    public function __construct(
        private readonly ClientPortalService $clientPortalService,
    ) {
    }

    public function index(): View
    {
        return view('client-portal.index', [
            'portal' => $this->clientPortalService->getPortal(),
        ]);
    }

    public function dashboard(Request $request): View
    {
        $dashboard = $this->clientPortalService->getDashboard($request->user());

        abort_if($dashboard === null, 403);

        return view('client-portal.dashboard', [
            'dashboard' => $dashboard,
        ]);
    }

    public function show(Request $request, string $slug): View
    {
        $engagement = $this->clientPortalService->getEngagement($request->user(), $slug);

        abort_if($engagement === null, 404);

        return view('client-portal.show', [
            'engagement' => $engagement,
            'dashboard' => $this->clientPortalService->getDashboard($request->user()),
        ]);
    }
}
