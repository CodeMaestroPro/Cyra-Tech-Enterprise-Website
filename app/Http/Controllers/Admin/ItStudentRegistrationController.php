<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ItStudentRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ItStudentRegistrationController extends Controller
{
    public function __construct(
        private readonly ItStudentRegistrationService $itStudentRegistrationService,
    ) {
    }

    public function index(): View
    {
        return view('admin.it.index', [
            'workspace' => $this->itStudentRegistrationService->getAdminWorkspace(),
        ]);
    }

    public function show(string $reference): View|RedirectResponse
    {
        $registration = $this->itStudentRegistrationService->findRegistrationByReference($reference);

        if ($registration === null) {
            return redirect()
                ->route('admin.it.index')
                ->with('error', 'Registration not found.');
        }

        return view('admin.it.show', [
            'registration' => $registration,
        ]);
    }
}
