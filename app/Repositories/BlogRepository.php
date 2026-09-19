<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Repositories\Contracts\BlogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BlogRepository extends BaseRepository implements BlogRepositoryInterface
{
    public function __construct(Blog $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Blog
    {
        return $this->model->where('slug', $slug)->with(['client', 'author'])->first();
    }

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['client', 'author']);

        if (! empty($filters['search'])) {
            $searchTerm = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('excerpt', 'like', $searchTerm)
                  ->orWhere('content', 'like', $searchTerm);
            });
        }

        if (! empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }

        if (! empty($filters['author_id'])) {
            $query->where('author_id', $filters['author_id']);
        }

        if (array_key_exists('status', $filters) && $filters['status'] !== null) {
            $query->where('status', (bool) $filters['status']);
        }

        return $query->paginate($perPage);
    }
}
