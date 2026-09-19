<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function getAllCategories(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->categoryRepository->getFiltered($filters, $perPage);
    }

    public function getCategoryById(int $id): ?Category
    {
        return $this->categoryRepository->findById($id, ['*'], ['parent', 'children', 'products']);
    }

    public function getCategoryBySlug(string $slug): ?Category
    {
        return $this->categoryRepository->findBySlug($slug);
    }

    public function createCategory(array $data): Category
    {
        if (empty($data['slug']) && ! empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (! isset($data['status'])) {
            $data['status'] = true;
        }

        /** @var Category $category */
        $category = $this->categoryRepository->create($data);
        return $category->load(['parent', 'children']);
    }

    public function updateCategory(int $id, array $data): ?Category
    {
        if (isset($data['name']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        /** @var Category|null $category */
        $category = $this->categoryRepository->update($id, $data);
        return $category ? $category->load(['parent', 'children']) : null;
    }

    public function deleteCategory(int $id): bool
    {
        return $this->categoryRepository->delete($id);
    }
}
