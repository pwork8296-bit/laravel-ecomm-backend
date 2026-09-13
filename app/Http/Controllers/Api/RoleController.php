<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\SyncRolePermissionsRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService
    ) {}

    /**
     * List roles.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $roles = $this->roleService->getAllRoles($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $roles,
        ]);
    }

    /**
     * View role by ID.
     */
    public function show(int $id): JsonResponse
    {
        $role = $this->roleService->getRoleById($id);

        if (! $role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Role not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $role,
        ]);
    }

    /**
     * Create a new role.
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->createRole($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully.',
            'data' => $role,
        ], 201);
    }

    /**
     * Update role by ID.
     */
    public function update(UpdateRoleRequest $request, int $id): JsonResponse
    {
        $role = $this->roleService->updateRole($id, $request->validated());

        if (! $role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Role not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully.',
            'data' => $role,
        ]);
    }

    /**
     * Delete role by ID.
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->roleService->deleteRole($id);

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Role not found or could not be deleted.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully.',
        ]);
    }

    /**
     * Sync permissions to role.
     */
    public function syncPermissions(SyncRolePermissionsRequest $request, int $id): JsonResponse
    {
        $role = $this->roleService->getRoleById($id);

        if (! $role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Role not found.',
            ], 404);
        }

        $result = $this->roleService->syncPermissions($id, $request->validated()['permissions']);

        return response()->json([
            'status' => 'success',
            'message' => 'Role permissions updated successfully.',
            'data' => [
                'changes' => $result,
                'role' => $this->roleService->getRoleById($id),
            ],
        ]);
    }
}
