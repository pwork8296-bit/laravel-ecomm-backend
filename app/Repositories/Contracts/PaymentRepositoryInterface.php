<?php

namespace App\Repositories\Contracts;

use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PaymentRepositoryInterface extends BaseRepositoryInterface
{
    public function findByOrderId(int $orderId): Collection;

    public function findByRazorpayOrderId(string $razorpayOrderId): ?Payment;

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
