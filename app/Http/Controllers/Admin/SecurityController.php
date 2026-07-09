<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SecurityService;
use Illuminate\View\View;

class SecurityController extends Controller
{
    public function __construct(private readonly SecurityService $securityService) {}

    public function __invoke(): View
    {
        return view('admin.security.index', ['security' => $this->securityService->getWorkspace()]);
    }
}
