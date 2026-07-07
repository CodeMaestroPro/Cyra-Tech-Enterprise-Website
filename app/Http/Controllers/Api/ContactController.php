<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactInquiryRequest;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __construct(
        private readonly ContactService $contactService,
    ) {
    }

    public function show(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->contactService->getContact(),
        ]);
    }

    public function store(ContactInquiryRequest $request): JsonResponse
    {
        $result = $this->contactService->submitInquiry(
            $request->validated(),
            $request->ip(),
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ], 201);
    }
}
