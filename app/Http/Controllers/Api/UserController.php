<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Constants\UserConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\HttpResponses;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HttpResponses;

    public function __construct(
        private readonly UserService $service
    ){}

    public function index(Request $request): JsonResponse
    {
        $users = $this->service->all($request->get('page_size', 5));

        return $this->success(UserResource::collection($users));
    }

    public function show(User $user): JsonResponse
    {
        return $this->success(new UserResource($user));
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        $user = $this->service->update($user, $request->validated());

        return $this->success(new UserResource($user));
    }

    public function destroy(User $user): JsonResponse
    {
        $deleted = $this->service->delete($user);

        return $this->success(UserConstants::DESTROY);
    }
}
