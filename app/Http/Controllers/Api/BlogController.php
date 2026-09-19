<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Services\BlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(
        protected BlogService $blogService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $filters = [
            'search' => $request->query('search'),
            'client_id' => $request->has('client_id') ? (int) $request->query('client_id') : null,
            'author_id' => $request->has('author_id') ? (int) $request->query('author_id') : null,
            'status' => $request->has('status') ? $request->query('status') : null,
        ];

        $blogs = $this->blogService->getAllBlogs($filters, $perPage);

        return response()->json([
            'status' => 'success',
            'data' => $blogs,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $blog = $this->blogService->getBlogById($id);

        if (! $blog) {
            return response()->json([
                'status' => 'error',
                'message' => 'Blog not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $blog,
        ]);
    }

    public function showBySlug(string $slug): JsonResponse
    {
        $blog = $this->blogService->getBlogBySlug($slug);

        if (! $blog) {
            return response()->json([
                'status' => 'error',
                'message' => "Blog with slug '{$slug}' not found.",
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $blog,
        ]);
    }

    public function store(StoreBlogRequest $request): JsonResponse
    {
        $currentUserId = auth()->id() ?? auth('sanctum')->id();
        $blog = $this->blogService->createBlog($request->validated(), $currentUserId);

        return response()->json([
            'status' => 'success',
            'message' => 'Blog created successfully.',
            'data' => $blog,
        ], 201);
    }

    public function update(UpdateBlogRequest $request, int $id): JsonResponse
    {
        $blog = $this->blogService->updateBlog($id, $request->validated());

        if (! $blog) {
            return response()->json([
                'status' => 'error',
                'message' => 'Blog not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Blog updated successfully.',
            'data' => $blog,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->blogService->deleteBlog($id);

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Blog not found or could not be deleted.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Blog deleted successfully.',
        ]);
    }
}
