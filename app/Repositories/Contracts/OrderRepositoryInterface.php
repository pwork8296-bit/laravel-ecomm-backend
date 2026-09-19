<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function findByOrderNumber(string $orderNumber): ?Order;

    public function getUserOrders(int $userId): Collection;

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
