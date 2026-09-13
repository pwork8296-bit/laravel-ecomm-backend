<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use App\Repositories\Contracts\RoleRepositoryInterface;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Role
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function syncPermissions(int $roleId, array $permissionIds): array
    {
        $role = $this->findById($roleId);
        if ($role) {
            return $role->permissions()->sync($permissionIds);
        }

        return [];
    }

    /**
     * Delete role and handle code-level relational cleanup.
     */
    public function delete(int $id): bool
    {
        $role = $this->findById($id);
        if ($role) {
            // Code-level cleanup: remove pivot associations and nullify user roles
            RolePermission::where('role_id', $id)->delete();
            User::where('role_id', $id)->update(['role_id' => null]);

            return (bool) $role->delete();
        }

        return false;
    }
}
