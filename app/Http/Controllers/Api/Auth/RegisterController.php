<?php

namespace App\Http\Controllers\API\Auth;

use App\Constants\AuthConstants;
use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Traits\HttpResponses;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    use HttpResponses;

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;

        event(new UserRegistered($user));

        return $this->success($success, AuthConstants::REGISTER, Response::HTTP_CREATED);
    }
}
