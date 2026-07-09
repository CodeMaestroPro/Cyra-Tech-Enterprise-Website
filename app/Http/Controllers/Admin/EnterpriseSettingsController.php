<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EnterpriseSettingsService;
use Illuminate\View\View;

class EnterpriseSettingsController extends Controller
{
    public function __construct(private readonly EnterpriseSettingsService $enterpriseSettingsService) {}

    public function __invoke(): View
    {
        return view('admin.enterprise-settings.index', [
            'settings' => $this->enterpriseSettingsService->getWorkspace(),
        ]);
    }
}
