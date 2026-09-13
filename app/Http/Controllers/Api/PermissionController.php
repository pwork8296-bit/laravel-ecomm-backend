<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionService $permissionService
    ) {}

    /**
     * List permissions.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $permissions = $this->permissionService->getAllPermissions($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $permissions,
        ]);
    }

    /**
     * View permission by ID.
     */
    public function show(int $id): JsonResponse
    {
        $permission = $this->permissionService->getPermissionById($id);

        if (! $permission) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permission not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $permission,
        ]);
    }

    /**
     * Create a new permission.
     */
    public function store(StorePermissionRequest $request): JsonResponse
    {
        $permission = $this->permissionService->createPermission($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Permission created successfully.',
            'data' => $permission,
        ], 201);
    }

    /**
     * Update permission by ID.
     */
    public function update(UpdatePermissionRequest $request, int $id): JsonResponse
    {
        $permission = $this->permissionService->updatePermission($id, $request->validated());

        if (! $permission) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permission not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Permission updated successfully.',
            'data' => $permission,
        ]);
    }

    /**
     * Delete permission by ID.
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->permissionService->deletePermission($id);

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permission not found or could not be deleted.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Permission deleted successfully.',
        ]);
    }
}
