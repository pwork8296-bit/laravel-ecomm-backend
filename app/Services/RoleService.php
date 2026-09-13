<?php

namespace App\Services;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class RoleService
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository
    ) {}

    /**
     * Get paginated roles with their permissions.
     */
    public function getAllRoles(int $perPage = 15): LengthAwarePaginator
    {
        return $this->roleRepository->paginate($perPage, ['*'], ['permissions']);
    }

    /**
     * Get a role by ID with permissions and users.
     */
    public function getRoleById(int $id): ?Role
    {
        /** @var Role|null $role */
        $role = $this->roleRepository->findById($id, ['*'], ['permissions', 'users']);
        return $role;
    }

    /**
     * Create a new role.
     */
    public function createRole(array $data): Role
    {
        if (empty($data['slug']) && ! empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (! isset($data['status'])) {
            $data['status'] = true;
        }

        $permissionIds = $data['permissions'] ?? null;
        unset($data['permissions']);

        /** @var Role $role */
        $role = $this->roleRepository->create($data);

        if (is_array($permissionIds) && count($permissionIds) > 0) {
            $this->roleRepository->syncPermissions($role->id, $permissionIds);
        }

        return $role->load('permissions');
    }

    /**
     * Update an existing role.
     */
    public function updateRole(int $id, array $data): ?Role
    {
        if (isset($data['name']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $permissionIds = $data['permissions'] ?? null;
        unset($data['permissions']);

        /** @var Role|null $role */
        $role = $this->roleRepository->update($id, $data);

        if ($role && is_array($permissionIds)) {
            $this->roleRepository->syncPermissions($role->id, $permissionIds);
        }

        return $role ? $role->load('permissions') : null;
    }

    /**
     * Sync permissions to role.
     */
    public function syncPermissions(int $roleId, array $permissionIds): array
    {
        return $this->roleRepository->syncPermissions($roleId, $permissionIds);
    }

    /**
     * Delete a role by ID (with code-level cascading).
     */
    public function deleteRole(int $id): bool
    {
        return $this->roleRepository->delete($id);
    }
}
