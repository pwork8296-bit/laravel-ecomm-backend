<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Models\RolePermission;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Permission
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function getByResource(string $resource): Collection
    {
        return $this->model->where('resource', $resource)->get();
    }

    /**
     * Delete permission and handle code-level relational cleanup.
     */
    public function delete(int $id): bool
    {
        $permission = $this->findById($id);
        if ($permission) {
            // Code-level cleanup: remove pivot associations
            RolePermission::where('permission_id', $id)->delete();

            return (bool) $permission->delete();
        }

        return false;
    }
}
