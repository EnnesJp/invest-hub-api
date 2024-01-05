<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait HttpResponses
{
    protected function getMeta ($response): array
    {
        return [
            'page_size' => $response->perPage(),
            'current_page' => $response->currentPage(),
            'total_pages' => $response->lastPage(),
            'total_count' => $response->total(),
        ];
    }

    protected function success(
        $data,
        string $message = null,
        int $code = ResponseAlias::HTTP_OK,
        array $meta = null
    ): JsonResponse {
        $content = [
            'data' => $data,
        ];

        if ($meta) {
            $content['meta'] = $meta;
        }

        return response()->json([
            'message' => $message,
            'content' => $content,
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
