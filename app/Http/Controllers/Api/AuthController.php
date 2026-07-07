<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthUserResource;
use App\Services\AuthService;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly RoleService $roleService,
    ) {
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new AuthUserResource($this->authService->profile($request->user())),
        ]);
    }

    public function permissions(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'permissions' => $request->user()->getPermissionSlugs()->values()->all(),
            ],
        ]);
    }

    public function roles(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->roleService->listRoles(),
        ]);
    }
}
