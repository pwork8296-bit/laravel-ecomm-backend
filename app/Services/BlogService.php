<?php

namespace App\Services;

use App\Models\Blog;
use App\Repositories\Contracts\BlogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class BlogService
{
    public function __construct(
        protected BlogRepositoryInterface $blogRepository
    ) {}

    public function getAllBlogs(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->blogRepository->getFiltered($filters, $perPage);
    }

    public function getBlogById(int $id): ?Blog
    {
        return $this->blogRepository->findById($id, ['*'], ['client', 'author']);
    }

    public function getBlogBySlug(string $slug): ?Blog
    {
        return $this->blogRepository->findBySlug($slug);
    }

    public function createBlog(array $data, ?int $currentUserId = null): Blog
    {
        if (empty($data['author_id']) && $currentUserId) {
            $data['author_id'] = $currentUserId;
        }

        if (empty($data['slug']) && ! empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if (! isset($data['status'])) {
            $data['status'] = true;
        }

        /** @var Blog $blog */
        $blog = $this->blogRepository->create($data);
        return $blog->load(['client', 'author']);
    }

    public function updateBlog(int $id, array $data): ?Blog
    {
        if (isset($data['title']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        /** @var Blog|null $blog */
        $blog = $this->blogRepository->update($id, $data);
        return $blog ? $blog->load(['client', 'author']) : null;
    }

    public function deleteBlog(int $id): bool
    {
        return $this->blogRepository->delete($id);
    }
}
