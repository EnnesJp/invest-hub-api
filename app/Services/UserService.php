<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        private readonly UserRepository $repository
    ) {}

    public function all(int $pageSize = 5): LengthAwarePaginator
    {
        return $this->repository->all($pageSize);
    }

    public function create(array $attributes): User
    {
        return $this->repository->create($attributes);
    }

    public function update(User $user, array $attributes): User
    {
        return $this->repository->update($user, $attributes);
    }

    public function delete(User $user): bool
    {
        return $this->repository->delete($user);
    }
}