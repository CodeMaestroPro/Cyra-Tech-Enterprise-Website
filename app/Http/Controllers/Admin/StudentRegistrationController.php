<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StudentRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentRegistrationController extends Controller
{
    public function __construct(
        private readonly StudentRegistrationService $studentRegistrationService,
    ) {
    }

    public function index(): View
    {
        return view('admin.students.index', [
            'workspace' => $this->studentRegistrationService->getAdminWorkspace(),
        ]);
    }

    public function show(string $reference): View|RedirectResponse
    {
        $registration = $this->studentRegistrationService->findRegistrationByReference($reference);

        if ($registration === null) {
            return redirect()
                ->route('admin.students.index')
                ->with('error', 'Registration not found.');
        }

        return view('admin.students.show', [
            'registration' => $registration,
        ]);
    }
}
