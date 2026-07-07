<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DesignSystemService;
use Illuminate\View\View;

class DesignSystemController extends Controller
{
    public function __construct(
        private readonly DesignSystemService $designSystemService,
    ) {
    }

    public function __invoke(): View
    {
        return view('admin.design-system.index', [
            'catalog' => $this->designSystemService->getCatalog(),
        ]);
    }
}
