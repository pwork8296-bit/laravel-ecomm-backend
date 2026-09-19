<?php

namespace App\Repositories\Contracts;

use App\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ContactRepositoryInterface extends BaseRepositoryInterface
{
    public function findByClientId(int $clientId): Collection;

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
