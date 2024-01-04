<?php

namespace App\Http\Controllers\API\Auth;

use App\Constants\AuthConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Traits\HttpResponses;
use App\Http\Resources\UserResource;

class LoginController extends Controller
{
    use HttpResponses;

    public function login(AuthRequest $request): JsonResponse
    {
        if (auth()->attempt($request->all())) {
            $user = auth()->user();

            $user->tokens()->delete();

            $success = $user->createToken('MyApp')->plainTextToken;

            return $this->success(['token' => $success, 'user' => new UserResource($user)], AuthConstants::LOGIN);
        }

        return $this->error([], AuthConstants::VALIDATION);
    }

    public function logout(): JsonResponse
    {
        $user = auth()->user();

        $user->tokens()->delete();

        return $this->success([], AuthConstants::LOGOUT);
    }

    public function details(): JsonResponse
    {
        $user = auth()->user();

        return $this->success($user, '');
    }
}
