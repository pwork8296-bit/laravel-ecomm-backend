<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\StoreCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $carts = $this->cartService->getAllCarts($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $carts,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $cart = $this->cartService->getCartById($id);

        if (! $cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart item not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $cart,
        ]);
    }

    public function userCart(Request $request, int $userId): JsonResponse
    {
        $status = $request->query('status');
        $summary = $this->cartService->getUserCartSummary($userId, $status);

        return response()->json([
            'status' => 'success',
            'data' => $summary,
        ]);
    }

    public function store(StoreCartRequest $request): JsonResponse
    {
        $currentUserId = auth()->id() ?? auth('sanctum')->id();
        $cart = $this->cartService->addToCart($request->validated(), $currentUserId);

        return response()->json([
            'status' => 'success',
            'message' => 'Item added to cart successfully.',
            'data' => $cart,
        ], 201);
    }

    public function update(UpdateCartRequest $request, int $id): JsonResponse
    {
        $cart = $this->cartService->updateCart($id, $request->validated());

        if (! $cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart item not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Cart updated successfully.',
            'data' => $cart,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->cartService->deleteCart($id);

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart item not found or could not be deleted.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Cart item deleted successfully.',
        ]);
    }

    public function clear(int $userId): JsonResponse
    {
        $this->cartService->clearUserCart($userId);

        return response()->json([
            'status' => 'success',
            'message' => 'User cart cleared successfully.',
        ]);
    }
}
