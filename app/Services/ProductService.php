<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function getAllProducts(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->productRepository->getFiltered($filters, $perPage);
    }

    public function getProductById(int $id): ?Product
    {
        return $this->productRepository->findById($id, ['*'], ['category']);
    }

    public function getProductBySku(string $sku): ?Product
    {
        return $this->productRepository->findBySku($sku);
    }

    public function createProduct(array $data): Product
    {
        if (! isset($data['status'])) {
            $data['status'] = true;
        }

        if (! isset($data['stock_quantity'])) {
            $data['stock_quantity'] = 0;
        }

        if (! isset($data['price'])) {
            $data['price'] = 0.00;
        }

        /** @var Product $product */
        $product = $this->productRepository->create($data);
        return $product->load('category');
    }

    public function updateProduct(int $id, array $data): ?Product
    {
        /** @var Product|null $product */
        $product = $this->productRepository->update($id, $data);
        return $product ? $product->load('category') : null;
    }

    public function deleteProduct(int $id): bool
    {
        return $this->productRepository->delete($id);
    }
}
