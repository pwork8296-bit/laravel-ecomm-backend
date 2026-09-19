<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    public function findByOrderId(int $orderId): Collection
    {
        return $this->model->where('order_id', $orderId)->with('order')->get();
    }

    public function findByRazorpayOrderId(string $razorpayOrderId): ?Payment
    {
        return $this->model->where('razorpay_order_id', $razorpayOrderId)->with('order')->first();
    }

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('order');

        if (! empty($filters['search'])) {
            $searchTerm = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('razorpay_order_id', 'like', $searchTerm)
                  ->orWhere('razorpay_payment_id', 'like', $searchTerm);
            });
        }

        if (! empty($filters['order_id'])) {
            $query->where('order_id', $filters['order_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }
}
