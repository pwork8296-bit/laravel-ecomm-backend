<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $filters = [
            'search' => $request->query('search'),
            'category_id' => $request->has('category_id') ? (int) $request->query('category_id') : null,
            'status' => $request->has('status') ? $request->query('status') : null,
            'variant' => $request->query('variant'),
        ];

        $products = $this->productService->getAllProducts($filters, $perPage);

        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (! $product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $product,
        ]);
    }

    public function showBySku(string $sku): JsonResponse
    {
        $product = $this->productService->getProductBySku($sku);

        if (! $product) {
            return response()->json([
                'status' => 'error',
                'message' => "Product with SKU '{$sku}' not found.",
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $product,
        ]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully.',
            'data' => $product,
        ], 201);
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $product = $this->productService->updateProduct($id, $request->validated());

        if (! $product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully.',
            'data' => $product,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->productService->deleteProduct($id);

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found or could not be deleted.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully.',
        ]);
    }
}
