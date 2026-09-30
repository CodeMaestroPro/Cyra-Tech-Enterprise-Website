<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItStudentRegistrationRequest;
use App\Services\ItStudentRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ItStudentRegistrationController extends Controller
{
    public function __construct(
        private readonly ItStudentRegistrationService $itStudentRegistrationService,
    ) {
    }

    public function show(): View
    {
        $page = $this->itStudentRegistrationService->getPage();
        $registration = null;

        if (session()->has('registration_reference')) {
            $registration = $this->itStudentRegistrationService->findRegistrationByReference(
                (string) session('registration_reference')
            );
        }

        return view('it.index', [
            'page' => $page,
            'registration' => $registration,
            'academicLevelOptions' => $this->itStudentRegistrationService->getAcademicLevelOptions(),
            'interestAreaOptions' => $this->itStudentRegistrationService->getInterestAreaOptions(),
            'availabilityOptions' => $this->itStudentRegistrationService->getAvailabilityOptions(),
        ]);
    }

    public function store(ItStudentRegistrationRequest $request): RedirectResponse
    {
        $result = $this->itStudentRegistrationService->submitRegistration(
            $request->validated(),
            $request->ip(),
        );

        return redirect()
            ->route('it')
            ->with('success', $result['message'])
            ->with('registration_reference', $result['reference']);
    }
}
