<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LogsService;
use Illuminate\View\View;

class LogsController extends Controller
{
    public function __construct(private readonly LogsService $logsService) {}

    public function __invoke(): View
    {
        return view('admin.logs.index', ['logs' => $this->logsService->getWorkspace()]);
    }
}
