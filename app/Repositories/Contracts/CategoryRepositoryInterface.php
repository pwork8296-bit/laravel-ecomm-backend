<?php

namespace App\Repositories\Contracts;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug(string $slug): ?Category;

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
