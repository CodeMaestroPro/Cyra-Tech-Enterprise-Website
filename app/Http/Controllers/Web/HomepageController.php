<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\HomepageService;
use Illuminate\View\View;

class HomepageController extends Controller
{
    public function __construct(
        private readonly HomepageService $homepageService,
    ) {
    }

    public function __invoke(): View
    {
        $homepage = $this->homepageService->getHomepage();

        return view('home.index', [
            'seo' => $homepage['seo'],
            'sections' => $homepage['sections'],
        ]);
    }
}
