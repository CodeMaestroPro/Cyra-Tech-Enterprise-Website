<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AiAssistantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiAssistantController extends Controller
{
    public function __construct(
        private readonly AiAssistantService $aiAssistantService,
    ) {
    }

    public function index(): View
    {
        return view('admin.ai-assistant.index', [
            'workspace' => $this->aiAssistantService->getWorkspace(),
        ]);
    }

    public function query(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'prompt' => ['nullable', 'string', 'max:100'],
        ]);

        $response = $this->aiAssistantService->respond(
            $validated['message'],
            $validated['prompt'] ?? null,
        );

        return response()->json([
            'success' => true,
            'data' => $response,
        ]);
    }
}
