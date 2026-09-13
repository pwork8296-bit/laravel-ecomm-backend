<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * List users.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $users = $this->userService->getAllUsers($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }

    /**
     * View user by ID.
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    /**
     * Create a new user.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully.',
            'data' => $user,
        ], 201);
    }

    /**
     * Update user by ID.
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->userService->updateUser($id, $request->validated());

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully.',
            'data' => $user,
        ]);
    }

    /**
     * Delete user by ID.
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->userService->deleteUser($id);

        if (! $deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found or could not be deleted.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully.',
        ]);
    }
}
