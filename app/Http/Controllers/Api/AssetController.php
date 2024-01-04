<?php

namespace App\Http\Controllers\Api;

use App\Constants\AuthConstants;
use App\Constants\AssetConstants;
use App\Http\Controllers\Controller;
use App\Http\Traits\Access;
use App\Http\Traits\HttpResponses;
use App\Http\Requests\Api\AssetRequest;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use App\Repositories\AssetRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use LDAP\Result;
use Symfony\Component\HttpFoundation\Response;

class AssetController extends Controller
{
    use Access;
    use HttpResponses;

    public function index(Request $request): JsonResponse
    {
        $assets = auth()
                    ->user()
                    ->assets()
                    ->latest()
                    ->paginate($request->per_page ?? 20);

        $meta = [
            'page_size' => $assets->perPage(),
            'current_page' => $assets->currentPage(),
            'total_pages' => $assets->lastPage(),
            'total_count' => $assets->total(),
        ];

        return $this->success(
            AssetResource::collection($assets),
            null,
            Response::HTTP_OK,
            $meta
        );
    }

    public function show(Asset $asset): JsonResponse
    {
        if (!$this->canAccess($asset)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        return $this->success(new AssetResource($asset));
    }

    public function store(
        AssetRequest $request,
        AssetRepository $repository
    ): JsonResponse {
        $asset = $repository->create($request->all());

        return $this->success(
            new AssetResource($asset),
            AssetConstants::STORE,
            Response::HTTP_CREATED
        );
    }

    public function update(
        AssetRequest $request,
        Asset $asset,
        AssetRepository $repository
    ): JsonResponse {
        if (!$this->canAccess($asset)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        $repository->update($request->all(), $request->all());

        return $this->success(
            new AssetResource($asset),
            AssetConstants::UPDATE
        );
    }

    public function destroy(Asset $asset, AssetRepository $repository): JsonResponse
    {
        if (!$this->canAccess($asset)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        $repository->forceDelete($asset);

        return $this->success(
            [],
            AssetConstants::DESTROY
        );
    }
}
