<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Get paginated users with their associated role.
     */
    public function getAllUsers(int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->paginate($perPage, ['*'], ['role']);
    }

    /**
     * Get a user by ID with role and its permissions.
     */
    public function getUserById(int $id): ?User
    {
        /** @var User|null $user */
        $user = $this->userRepository->findById($id, ['*'], ['role.permissions']);
        return $user;
    }

    /**
     * Create a new user.
     */
    public function createUser(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if (! isset($data['status'])) {
            $data['status'] = true;
        }

        /** @var User $user */
        $user = $this->userRepository->create($data);
        return $user->load('role');
    }

    /**
     * Update an existing user.
     */
    public function updateUser(int $id, array $data): ?User
    {
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        /** @var User|null $user */
        $user = $this->userRepository->update($id, $data);
        return $user ? $user->load('role') : null;
    }

    /**
     * Delete user by ID.
     */
    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }
}
