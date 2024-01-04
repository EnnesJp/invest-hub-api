<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait HttpResponses
{
    protected function success(
        $data,
        string $message = null,
        int $code = ResponseAlias::HTTP_OK,
        array $meta = null
    ): JsonResponse {
        return response()->json([
            'message' => $message,
            'content' => [
                'data' => $data,
                'meta' => $meta
            ],
        ], $code);
    }

    protected function error(
        $data,
        string $message = null,
        int $code = ResponseAlias::HTTP_BAD_REQUEST
    ): JsonResponse {
        return response()->json([
            'message' => $message,
            'content' => $data,
        ], $code);
    }
}
