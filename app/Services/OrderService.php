<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository
    ) {}

    public function getAllOrders(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->orderRepository->getFiltered($filters, $perPage);
    }

    public function getOrderById(int $id): ?Order
    {
        return $this->orderRepository->findById($id, ['*'], ['user', 'cart', 'payments']);
    }

    public function getOrderByNumber(string $orderNumber): ?Order
    {
        return $this->orderRepository->findByOrderNumber($orderNumber);
    }

    public function getUserOrders(int $userId): Collection
    {
        return $this->orderRepository->getUserOrders($userId);
    }

    public function createOrder(array $data, ?int $currentUserId = null): Order
    {
        if (empty($data['user_id']) && $currentUserId) {
            $data['user_id'] = $currentUserId;
        }

        if (is_array($data['products'] ?? null)) {
            $data['products'] = json_encode($data['products']);
        }

        if (empty($data['order_number'])) {
            $data['order_number'] = 'ORD-' . date('YmdHis') . '-' . strtoupper(Str::random(6));
        }

        if (! isset($data['status'])) {
            $data['status'] = 'pending';
        }

        if (! isset($data['currency'])) {
            $data['currency'] = 'INR';
        }

        if (! isset($data['amount'])) {
            $data['amount'] = 0.00;
        }

        /** @var Order $order */
        $order = $this->orderRepository->create($data);
        return $order->load(['user', 'cart']);
    }

    public function updateOrder(int $id, array $data): ?Order
    {
        if (is_array($data['products'] ?? null)) {
            $data['products'] = json_encode($data['products']);
        }

        /** @var Order|null $order */
        $order = $this->orderRepository->update($id, $data);
        return $order ? $order->load(['user', 'cart', 'payments']) : null;
    }

    public function deleteOrder(int $id): bool
    {
        return $this->orderRepository->delete($id);
    }
}
