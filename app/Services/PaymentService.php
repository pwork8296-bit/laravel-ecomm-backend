<?php

namespace App\Services;

use App\Models\Payment;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PaymentService
{
    public function __construct(
        protected PaymentRepositoryInterface $paymentRepository,
        protected OrderRepositoryInterface $orderRepository
    ) {}

    public function getAllPayments(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->paymentRepository->getFiltered($filters, $perPage);
    }

    public function getPaymentById(int $id): ?Payment
    {
        return $this->paymentRepository->findById($id, ['*'], ['order']);
    }

    public function getPaymentsByOrderId(int $orderId): Collection
    {
        return $this->paymentRepository->findByOrderId($orderId);
    }

    public function createPayment(array $data): Payment
    {
        $order = $this->orderRepository->findById($data['order_id']);

        if (empty($data['amount']) && $order) {
            $data['amount'] = $order->amount;
        }

        if (empty($data['currency']) && $order) {
            $data['currency'] = $order->currency;
        }

        if (! isset($data['status'])) {
            $data['status'] = 'pending';
        }

        /** @var Payment $payment */
        $payment = $this->paymentRepository->create($data);

        // Auto-update order status when payment is successful
        if (in_array(strtolower($payment->status), ['captured', 'paid', 'success'], true) && $order) {
            $this->orderRepository->update($order->id, ['status' => 'paid']);
        }

        return $payment->load('order');
    }

    public function updatePayment(int $id, array $data): ?Payment
    {
        /** @var Payment|null $payment */
        $payment = $this->paymentRepository->update($id, $data);

        if ($payment && in_array(strtolower($payment->status), ['captured', 'paid', 'success'], true)) {
            $this->orderRepository->update($payment->order_id, ['status' => 'paid']);
        }

        return $payment ? $payment->load('order') : null;
    }

    public function deletePayment(int $id): bool
    {
        return $this->paymentRepository->delete($id);
    }
}
