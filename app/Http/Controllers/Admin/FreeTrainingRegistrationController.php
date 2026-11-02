<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FreeTrainingRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FreeTrainingRegistrationController extends Controller
{
    public function __construct(
        private readonly FreeTrainingRegistrationService $freeTrainingRegistrationService,
    ) {
    }

    public function index(): View
    {
        return view('admin.free-training.index', [
            'workspace' => $this->freeTrainingRegistrationService->getAdminWorkspace(),
        ]);
    }

    public function show(string $reference): View|RedirectResponse
    {
        $registration = $this->freeTrainingRegistrationService->findRegistrationByReference($reference);

        if ($registration === null) {
            return redirect()
                ->route('admin.free-training.index')
                ->with('error', 'Registration not found.');
        }

        return view('admin.free-training.show', [
            'registration' => $registration,
        ]);
    }
}
