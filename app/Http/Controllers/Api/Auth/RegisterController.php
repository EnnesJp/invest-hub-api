<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Auth;

use App\Constants\AuthConstants;
use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Traits\HttpResponses;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    use HttpResponses;

    public function __construct(
        protected readonly UserService $service
    ) {
    }

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $response = $this->service->create($request->validated());

        return $this->success($response, AuthConstants::REGISTER, Response::HTTP_CREATED);
    }
}
