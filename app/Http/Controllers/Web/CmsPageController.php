<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CmsService;
use Illuminate\View\View;

class CmsPageController extends Controller
{
    public function __construct(
        private readonly CmsService $cmsService,
    ) {
    }

    public function show(string $slug): View
    {
        $page = $this->cmsService->getPublicPage($slug);

        abort_if($page === null, 404);

        return view('pages.show', [
            'page' => $page,
        ]);
    }
}
