<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCrmLeadRequest;
use App\Http\Requests\UpdateCrmLeadRequest;
use App\Http\Requests\UpdateCrmLeadStageRequest;
use App\Services\CrmService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function __construct(
        private readonly CrmService $crmService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->crmService->getPipeline($request->query('stage')),
        ]);
    }

    public function show(string $reference): JsonResponse
    {
        $lead = $this->crmService->getLead($reference);

        if ($lead === null) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $lead,
        ]);
    }

    public function store(StoreCrmLeadRequest $request): JsonResponse
    {
        $lead = $this->crmService->createLead(
            $request->validated(),
            $request->user(),
        );

        return response()->json([
            'success' => true,
            'data' => $lead,
        ], 201);
    }

    public function update(UpdateCrmLeadRequest $request, string $reference): JsonResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $lead = $this->crmService->updateLead($reference, $data);

        if ($lead === null) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $lead,
        ]);
    }

    public function updateStage(UpdateCrmLeadStageRequest $request, string $reference): JsonResponse
    {
        $lead = $this->crmService->updateStage($reference, $request->validated()['pipeline_stage']);

        if ($lead === null) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $lead,
        ]);
    }

    public function convertInquiry(Request $request, int $inquiry): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('crm.create') ?? false, 403);

        $lead = $this->crmService->convertInquiry($inquiry, $request->user());

        if ($lead === null) {
            return response()->json([
                'success' => false,
                'message' => 'Inquiry not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $lead,
        ], 201);
    }
}
