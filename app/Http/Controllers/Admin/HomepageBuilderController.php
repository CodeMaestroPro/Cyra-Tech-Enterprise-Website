<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HomepageBuilderService;
use Illuminate\View\View;

class HomepageBuilderController extends Controller
{
    public function __construct(
        private readonly HomepageBuilderService $homepageBuilderService,
    ) {
    }

    public function __invoke(): View
    {
        return view('admin.homepage-builder.index', [
            'builder' => $this->homepageBuilderService->getWorkspace(),
        ]);
    }
}
