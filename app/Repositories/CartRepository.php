<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    public function __construct(Cart $model)
    {
        parent::__construct($model);
    }

    public function getUserCart(int $userId, ?string $status = null): Collection
    {
        $query = $this->model->where('user_id', $userId)->with('product');

        if ($status !== null) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    public function clearUserCart(int $userId): bool
    {
        return (bool) $this->model->where('user_id', $userId)->delete();
    }

    public function findByUserAndProduct(int $userId, int $productId, ?string $variant = null): ?Cart
    {
        $query = $this->model->where('user_id', $userId)->where('product_id', $productId);

        if ($variant !== null) {
            $query->where('variant', $variant);
        }

        return $query->first();
    }
}
