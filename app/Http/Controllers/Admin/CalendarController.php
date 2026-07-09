<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CalendarService;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __construct(private readonly CalendarService $calendarService) {}

    public function __invoke(): View
    {
        return view('admin.calendar.index', ['calendar' => $this->calendarService->getWorkspace()]);
    }
}
