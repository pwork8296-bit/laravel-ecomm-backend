<?php

namespace App\Repositories\Contracts;

use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ClientRepositoryInterface extends BaseRepositoryInterface
{
    public function findByDomain(string $domain): ?Client;

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
