<?php

namespace App\Repositories\Contracts;

use App\Models\Role;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find role by slug.
     */
    public function findBySlug(string $slug): ?Role;

    /**
     * Sync permissions for a role (code-level relation).
     */
    public function syncPermissions(int $roleId, array $permissionIds): array;
}
