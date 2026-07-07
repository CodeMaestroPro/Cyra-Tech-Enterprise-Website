<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PagePreviewController extends Controller
{
    public function show(string $slug): View
    {
        $module = collect(config('cyra.modules', []))
            ->firstWhere('slug', $slug);

        return view('pages.preview', [
            'slug' => $slug,
            'module' => $module,
            'title' => $module['name'] ?? ucfirst(str_replace('-', ' ', $slug)),
        ]);
    }
}
