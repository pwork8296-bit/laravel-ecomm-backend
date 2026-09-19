<?php

namespace App\Repositories\Contracts;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;

interface CartRepositoryInterface extends BaseRepositoryInterface
{
    public function getUserCart(int $userId, ?string $status = null): Collection;

    public function clearUserCart(int $userId): bool;

    public function findByUserAndProduct(int $userId, int $productId, ?string $variant = null): ?Cart;
}
