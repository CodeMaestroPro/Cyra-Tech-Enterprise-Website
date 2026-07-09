<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateApplicantRequest;
use App\Services\ApplicantsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ApplicantsController extends Controller
{
    public function __construct(
        private readonly ApplicantsService $applicantsService,
    ) {
    }

    public function index(): View
    {
        return view('admin.applicants.index', [
            'applicants' => $this->applicantsService->getWorkspace(),
        ]);
    }

    public function edit(string $reference): View|RedirectResponse
    {
        $applicant = $this->applicantsService->getAdminApplicant($reference);

        if ($applicant === null) {
            return redirect()
                ->route('admin.applicants.index')
                ->with('error', 'Applicant not found.');
        }

        return view('admin.applicants.edit', [
            'applicant' => $applicant,
            'statusOptions' => $this->applicantsService->getStatusOptions(),
        ]);
    }

    public function update(UpdateApplicantRequest $request, string $reference): RedirectResponse
    {
        $applicant = $this->applicantsService->updateApplicant($reference, $request->validated());

        if ($applicant === null) {
            return redirect()
                ->route('admin.applicants.index')
                ->with('error', 'Applicant not found.');
        }

        return redirect()
            ->route('admin.applicants.edit', $applicant['reference'])
            ->with('success', 'Applicant updated successfully.');
    }

    public function destroy(string $reference): RedirectResponse
    {
        abort_unless(auth()->user()?->hasPermission('crm.update') ?? false, 403);

        if (! $this->applicantsService->deleteApplicant($reference)) {
            return redirect()
                ->route('admin.applicants.index')
                ->with('error', 'Applicant not found.');
        }

        return redirect()
            ->route('admin.applicants.index')
            ->with('success', 'Applicant deleted successfully.');
    }
}
