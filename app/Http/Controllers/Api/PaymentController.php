<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $filters = [
            'search' => $request->query('search'),
            'order_id' => $request->has('order_id') ? (int) $request->query('order_id') : null,
            'status' => $request->query('status'),
        ];

        $payments = $this->paymentService->getAllPayments($filters, $perPage);

        return response()->json([
            'status' => 'success',
            'data' => $payments,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $payment = $this->paymentService->getPaymentById($id);

        if (! $payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $payment,
        ]);
    }

    public function byOrder(int $orderId): JsonResponse
    {
        $payments = $this->paymentService->getPaymentsByOrderId($orderId);

        return response()->json([
            'status' => 'success',
            'data' => $payments,
        ]);
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        $payment = $this->paymentService->createPayment($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Payment created successfully.',
            'data' => $payment,
        ], 201);
    }

    public function update(UpdatePaymentRequest $request, int $id): JsonResponse
    {
        $payment = $this->paymentService->updatePayment($id, $request->validated());

        if (! $payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Payment updated successfully.',
            'data' => $payment,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->paymentService->deletePayment($id);

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found or could not be deleted.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Payment deleted successfully.',
        ]);
    }
}
