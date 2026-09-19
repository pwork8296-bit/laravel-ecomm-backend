<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $filters = [
            'search' => $request->query('search'),
            'parent_id' => $request->has('parent_id') ? (int) $request->query('parent_id') : null,
            'status' => $request->has('status') ? $request->query('status') : null,
        ];

        $categories = $this->categoryService->getAllCategories($filters, $perPage);

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $category = $this->categoryService->getCategoryById($id);

        if (! $category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $category,
        ]);
    }

    public function showBySlug(string $slug): JsonResponse
    {
        $category = $this->categoryService->getCategoryBySlug($slug);

        if (! $category) {
            return response()->json([
                'status' => 'error',
                'message' => "Category with slug '{$slug}' not found.",
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $category,
        ]);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->createCategory($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully.',
            'data' => $category,
        ], 201);
    }

    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        $category = $this->categoryService->updateCategory($id, $request->validated());

        if (! $category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully.',
            'data' => $category,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->categoryService->deleteCategory($id);

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found or could not be deleted.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully.',
        ]);
    }
}
