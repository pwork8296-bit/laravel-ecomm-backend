<?php

namespace App\Services;

use App\Models\Permission;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class PermissionService
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository
    ) {}

    /**
     * Get paginated permissions.
     */
    public function getAllPermissions(int $perPage = 15): LengthAwarePaginator
    {
        return $this->permissionRepository->paginate($perPage);
    }

    /**
     * Get a permission by ID with associated roles.
     */
    public function getPermissionById(int $id): ?Permission
    {
        /** @var Permission|null $permission */
        $permission = $this->permissionRepository->findById($id, ['*'], ['roles']);
        return $permission;
    }

    /**
     * Create a new permission.
     */
    public function createPermission(array $data): Permission
    {
        if (empty($data['slug']) && ! empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        /** @var Permission $permission */
        $permission = $this->permissionRepository->create($data);
        return $permission;
    }

    /**
     * Update an existing permission.
     */
    public function updatePermission(int $id, array $data): ?Permission
    {
        if (isset($data['name']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        /** @var Permission|null $permission */
        $permission = $this->permissionRepository->update($id, $data);
        return $permission;
    }

    /**
     * Delete permission by ID (with code-level cleanup).
     */
    public function deletePermission(int $id): bool
    {
        return $this->permissionRepository->delete($id);
    }
}
