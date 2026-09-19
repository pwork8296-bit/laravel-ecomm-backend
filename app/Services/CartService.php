<?php

namespace App\Services;

use App\Models\Cart;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CartService
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository,
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function getAllCarts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->cartRepository->paginate($perPage, ['*'], ['user', 'product']);
    }

    public function getCartById(int $id): ?Cart
    {
        return $this->cartRepository->findById($id, ['*'], ['user', 'product']);
    }

    public function getUserCartSummary(int $userId, ?string $status = null): array
    {
        $items = $this->cartRepository->getUserCart($userId, $status);
        $totalItems = $items->sum(fn ($item) => (int) ($item->quantity ?? 0));
        $subtotal = round($items->sum(fn ($item) => (float) ($item->price ?? 0) * (int) ($item->quantity ?? 0)), 2);

        return [
            'total_items' => $totalItems,
            'subtotal' => $subtotal,
            'items' => $items,
        ];
    }

    public function addToCart(array $data, ?int $currentUserId = null): Cart
    {
        if (empty($data['user_id']) && $currentUserId) {
            $data['user_id'] = $currentUserId;
        }

        if (is_array($data['products'] ?? null)) {
            $data['products'] = json_encode($data['products']);
        }

        if (! isset($data['status'])) {
            $data['status'] = '1';
        }

        $productId = $data['product_id'] ?? null;
        if ($productId) {
            $product = $this->productRepository->findById($productId);
            if ($product && (empty($data['price']) || (float) $data['price'] === 0.0)) {
                $data['price'] = $product->price;
            }

            // Check if same product & variant already in user's cart
            $existing = $this->cartRepository->findByUserAndProduct(
                (int) $data['user_id'],
                (int) $productId,
                $data['variant'] ?? null
            );

            if ($existing) {
                $newQuantity = ($existing->quantity ?? 0) + ($data['quantity'] ?? 1);
                $updateData = ['quantity' => $newQuantity];
                if (! empty($data['price'])) {
                    $updateData['price'] = $data['price'];
                }
                if (! empty($data['products'])) {
                    $updateData['products'] = $data['products'];
                }

                /** @var Cart $updated */
                $updated = $this->cartRepository->update($existing->id, $updateData);
                return $updated->load('product');
            }
        }

        /** @var Cart $cart */
        $cart = $this->cartRepository->create($data);
        return $cart->load('product');
    }

    public function updateCart(int $id, array $data): ?Cart
    {
        if (is_array($data['products'] ?? null)) {
            $data['products'] = json_encode($data['products']);
        }

        /** @var Cart|null $cart */
        $cart = $this->cartRepository->update($id, $data);
        return $cart ? $cart->load('product') : null;
    }

    public function deleteCart(int $id): bool
    {
        return $this->cartRepository->delete($id);
    }

    public function clearUserCart(int $userId): bool
    {
        return $this->cartRepository->clearUserCart($userId);
    }
}
