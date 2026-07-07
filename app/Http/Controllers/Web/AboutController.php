<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AboutService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __construct(
        private readonly AboutService $aboutService,
    ) {
    }

    public function show(Request $request, string $slug = 'overview'): View
    {
        $page = $this->aboutService->getPage($slug);

        abort_if($page === null, 404);

        return view('about.show', [
            'page' => $page,
            'aboutNav' => $this->aboutService->getNavigation($slug),
        ]);
    }
}
