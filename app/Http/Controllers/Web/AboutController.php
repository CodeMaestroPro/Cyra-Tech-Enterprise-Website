<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AboutService;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __construct(
        private readonly AboutService $aboutService,
    ) {
    }

    public function show(): View
    {
        $page = $this->aboutService->getAboutPage();

        abort_if($page === null, 404);

        return view('about.show', [
            'page' => $page,
        ]);
    }
}
