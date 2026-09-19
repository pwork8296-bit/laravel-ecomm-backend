<?php

namespace App\Repositories\Contracts;

use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BlogRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug(string $slug): ?Blog;

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
