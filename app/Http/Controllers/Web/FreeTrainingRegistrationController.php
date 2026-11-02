<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\FreeTrainingRegistrationRequest;
use App\Services\FreeTrainingRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FreeTrainingRegistrationController extends Controller
{
    public function __construct(
        private readonly FreeTrainingRegistrationService $freeTrainingRegistrationService,
    ) {
    }

    public function show(): View
    {
        $page = $this->freeTrainingRegistrationService->getPage();
        $registration = null;

        if (session()->has('free_training_reference')) {
            $registration = $this->freeTrainingRegistrationService->findRegistrationByReference(
                (string) session('free_training_reference')
            );
        }

        return view('free-training.index', [
            'page' => $page,
            'registration' => $registration,
            'programs' => $this->freeTrainingRegistrationService->getPrograms(),
            'genderOptions' => $this->freeTrainingRegistrationService->getGenderOptions(),
            'stateOptions' => $this->freeTrainingRegistrationService->getStateOptions(),
            'skillLevelOptions' => $this->freeTrainingRegistrationService->getSkillLevelOptions(),
        ]);
    }

    public function store(FreeTrainingRegistrationRequest $request): RedirectResponse
    {
        $result = $this->freeTrainingRegistrationService->submitRegistration(
            $request->validated(),
            $request->ip(),
        );

        return redirect()
            ->route('free-training')
            ->with('success', $result['message'])
            ->with('free_training_reference', $result['reference']);
    }

    public function verify(string $reference, string $token): View
    {
        $registration = $this->freeTrainingRegistrationService->findVerifiedRegistration($reference, $token);

        return view('free-training.verify', [
            'registration' => $registration,
            'valid' => $registration !== null,
        ]);
    }
}
