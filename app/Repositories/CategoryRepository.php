<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Category
    {
        return $this->model->where('slug', $slug)->with(['parent', 'children', 'products'])->first();
    }

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['parent', 'children']);

        if (! empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (array_key_exists('parent_id', $filters) && $filters['parent_id'] !== null) {
            $query->where('parent_id', $filters['parent_id']);
        }

        if (array_key_exists('status', $filters) && $filters['status'] !== null) {
            $query->where('status', (bool) $filters['status']);
        }

        return $query->paginate($perPage);
    }
}
