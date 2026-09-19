<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $filters = [
            'search' => $request->query('search'),
            'user_id' => $request->has('user_id') ? (int) $request->query('user_id') : null,
            'cart_id' => $request->has('cart_id') ? (int) $request->query('cart_id') : null,
            'status' => $request->query('status'),
        ];

        $orders = $this->orderService->getAllOrders($filters, $perPage);

        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $order = $this->orderService->getOrderById($id);

        if (! $order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }

    public function showByNumber(string $orderNumber): JsonResponse
    {
        $order = $this->orderService->getOrderByNumber($orderNumber);

        if (! $order) {
            return response()->json([
                'status' => 'error',
                'message' => "Order with number '{$orderNumber}' not found.",
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }

    public function userOrders(int $userId): JsonResponse
    {
        $orders = $this->orderService->getUserOrders($userId);

        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $currentUserId = auth()->id() ?? auth('sanctum')->id();
        $order = $this->orderService->createOrder($request->validated(), $currentUserId);

        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully.',
            'data' => $order,
        ], 201);
    }

    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        $order = $this->orderService->updateOrder($id, $request->validated());

        if (! $order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order updated successfully.',
            'data' => $order,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->orderService->deleteOrder($id);

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found or could not be deleted.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order deleted successfully.',
        ]);
    }
}
