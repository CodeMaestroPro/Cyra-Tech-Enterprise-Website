<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCrmLeadRequest;
use App\Http\Requests\UpdateCrmLeadRequest;
use App\Http\Requests\UpdateCrmLeadStageRequest;
use App\Models\User;
use App\Services\CrmService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CrmController extends Controller
{
    public function __construct(
        private readonly CrmService $crmService,
    ) {
    }

    public function index(Request $request): View
    {
        return view('admin.crm.index', [
            'pipeline' => $this->crmService->getPipeline($request->query('stage')),
            'stageFilter' => $request->query('stage', 'all'),
        ]);
    }

    public function create(): View
    {
        return view('admin.crm.create', [
            'options' => $this->formOptions(),
        ]);
    }

    public function store(StoreCrmLeadRequest $request): RedirectResponse
    {
        $lead = $this->crmService->createLead(
            $request->validated(),
            $request->user(),
        );

        return redirect()
            ->route('admin.crm.edit', $lead['reference'])
            ->with('success', 'Lead created successfully.');
    }

    public function edit(string $reference): View|RedirectResponse
    {
        $lead = $this->crmService->getLead($reference);

        if ($lead === null) {
            return redirect()
                ->route('admin.crm.index')
                ->with('error', 'Lead not found.');
        }

        return view('admin.crm.edit', [
            'lead' => $lead,
            'options' => $this->formOptions(),
        ]);
    }

    public function update(UpdateCrmLeadRequest $request, string $reference): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $lead = $this->crmService->updateLead($reference, $data);

        if ($lead === null) {
            return redirect()
                ->route('admin.crm.index')
                ->with('error', 'Lead not found.');
        }

        return redirect()
            ->route('admin.crm.edit', $lead['reference'])
            ->with('success', 'Lead updated successfully.');
    }

    public function updateStage(UpdateCrmLeadStageRequest $request, string $reference): RedirectResponse
    {
        $lead = $this->crmService->updateStage($reference, $request->validated()['pipeline_stage']);

        if ($lead === null) {
            return redirect()
                ->route('admin.crm.index')
                ->with('error', 'Lead not found.');
        }

        return redirect()
            ->route('admin.crm.index')
            ->with('success', 'Lead moved to '.$lead['pipeline_stage_label'].'.');
    }

    public function convertInquiry(Request $request, int $inquiry): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('crm.create') ?? false, 403);

        $lead = $this->crmService->convertInquiry($inquiry, $request->user());

        if ($lead === null) {
            return redirect()
                ->route('admin.crm.index')
                ->with('error', 'Inquiry not found.');
        }

        return redirect()
            ->route('admin.crm.edit', $lead['reference'])
            ->with('success', 'Contact inquiry converted to CRM lead.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'sources' => config('cyra.crm.sources', []),
            'pipeline_stages' => config('cyra.crm.pipeline_stages', []),
            'priorities' => config('cyra.crm.priorities', []),
            'assignees' => User::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->all(),
        ];
    }
}
