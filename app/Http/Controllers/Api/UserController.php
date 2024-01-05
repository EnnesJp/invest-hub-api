<?php

namespace App\Http\Controllers\Api;

use App\Constants\UserConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\HttpResponses;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HttpResponses;

    public function index(Request $request): JsonResponse
    {
        $users = User::query()->paginate($request->page_size ?? 5);

        return $this->success(UserResource::collection($users));
    }

    public function show(User $user): JsonResponse
    {
        return $this->success(new UserResource($user));
    }

    public function update(UserRequest $request, User $user, UserRepository $repository): JsonResponse
    {
        $user = $repository->update($user, $request->only([
            'name',
            'email',
        ]));

        return $this->success(new UserResource($user));
    }

    public function destroy(User $user, UserRepository $repository): JsonResponse
    {
        $deleted = $repository->delete($user);

        return $this->success(UserConstants::DESTROY);
    }
}
