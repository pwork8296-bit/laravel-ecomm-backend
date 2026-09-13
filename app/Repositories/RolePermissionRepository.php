<?php

namespace App\Repositories;

use App\Models\RolePermission;
use App\Repositories\Contracts\RolePermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RolePermissionRepository implements RolePermissionRepositoryInterface
{
    public function getByRoleId(int $roleId): Collection
    {
        return RolePermission::with('permission')->where('role_id', $roleId)->get();
    }

    public function assign(int $roleId, int $permissionId): RolePermission
    {
        return RolePermission::firstOrCreate([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
        ]);
    }

    public function revoke(int $roleId, int $permissionId): bool
    {
        return (bool) RolePermission::where('role_id', $roleId)
            ->where('permission_id', $permissionId)
            ->delete();
    }

    public function exists(int $roleId, int $permissionId): bool
    {
        return RolePermission::where('role_id', $roleId)
            ->where('permission_id', $permissionId)
            ->exists();
    }
}
