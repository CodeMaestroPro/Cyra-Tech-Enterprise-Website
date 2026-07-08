<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RunOptimizationActionRequest;
use App\Services\TestingOptimizationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OptimizationController extends Controller
{
    public function __construct(
        private readonly TestingOptimizationService $testingOptimizationService,
    ) {
    }

    public function index(): View
    {
        return view('admin.optimization.index', [
            'dashboard' => $this->testingOptimizationService->getDashboard(),
        ]);
    }

    public function runAction(RunOptimizationActionRequest $request): RedirectResponse
    {
        $result = $this->testingOptimizationService->runAction(
            $request->validated()['action'],
            $request->user(),
        );

        if (! ($result['success'] ?? false)) {
            return redirect()
                ->route('admin.optimization.index')
                ->with('error', $result['message'] ?? 'Optimization action failed.');
        }

        return redirect()
            ->route('admin.optimization.index')
            ->with('success', $result['message']);
    }
}
