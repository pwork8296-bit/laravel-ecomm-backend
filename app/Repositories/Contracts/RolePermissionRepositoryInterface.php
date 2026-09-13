<?php

namespace App\Repositories\Contracts;

use App\Models\RolePermission;
use Illuminate\Database\Eloquent\Collection;

interface RolePermissionRepositoryInterface
{
    /**
     * Get all permissions for a specific role.
     */
    public function getByRoleId(int $roleId): Collection;

    /**
     * Assign a permission to a role.
     */
    public function assign(int $roleId, int $permissionId): RolePermission;

    /**
     * Revoke a permission from a role.
     */
    public function revoke(int $roleId, int $permissionId): bool;

    /**
     * Check if a role has a specific permission ID.
     */
    public function exists(int $roleId, int $permissionId): bool;
}
