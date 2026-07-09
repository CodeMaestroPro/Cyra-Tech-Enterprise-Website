<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CompanyPulseService;
use Illuminate\View\View;

class CompanyPulseController extends Controller
{
    public function __construct(
        private readonly CompanyPulseService $companyPulseService,
    ) {
    }

    public function __invoke(): View
    {
        return view('admin.company-pulse.index', [
            'pulse' => $this->companyPulseService->getDashboard(),
        ]);
    }
}
