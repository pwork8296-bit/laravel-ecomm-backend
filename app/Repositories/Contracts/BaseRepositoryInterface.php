<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    /**
     * Get all records.
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Get paginated records.
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = []): LengthAwarePaginator;

    /**
     * Find a record by ID.
     */
    public function findById(int $id, array $columns = ['*'], array $relations = []): ?Model;

    /**
     * Create a new record.
     */
    public function create(array $data): Model;

    /**
     * Update an existing record.
     */
    public function update(int $id, array $data): ?Model;

    /**
     * Delete a record by ID.
     */
    public function delete(int $id): bool;
}
