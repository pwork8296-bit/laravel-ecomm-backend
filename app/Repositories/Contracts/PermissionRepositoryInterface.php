<?php

namespace App\Repositories\Contracts;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

interface PermissionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find permission by slug.
     */
    public function findBySlug(string $slug): ?Permission;

    /**
     * Get permissions grouped by resource.
     */
    public function getByResource(string $resource): Collection;
}
