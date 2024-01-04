<?php

namespace App\Http\Controllers\Api;

use App\Constants\AuthConstants;
use App\Constants\PortfolioConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PortfolioRequest;
use App\Http\Resources\PortfolioResource;
use App\Http\Traits\Access;
use App\Http\Traits\HttpResponses;
use App\Models\Portfolio;
use App\Repositories\PortfolioRepository;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    use Access;
    use HttpResponses;

    public function index(Request $request): JsonResponse
    {
        $portfolios = auth()
                        ->user()
                        ->portfolio()
                        ->latest()
                        ->pagination($request->per_page, $request->page);

        return $this->success(
            PortfolioResource::collection($portfolios),
            null,
            Response::HTTP_OK
        );
    }

    public function show(Portfolio $portfolio): JsonResponse
    {
        if (!$this->canAccess($portfolio)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        return $this->success(new PortfolioResource($portfolio));
    }

    public function store(
        PortfolioRequest $request,
        PortfolioRepository $repository
    ): JsonResponse {
        $portfolio = $repository->create($request->all());

        return $this->success(
            new PortfolioResource($portfolio),
            PortfolioConstants::STORE,
            Response::HTTP_CREATED
        );
    }

    public function update(
        PortfolioRequest $request,
        Portfolio $portfolio,
        PortfolioRepository $repository
    ): JsonResponse {
        if (!$this->canAccess($portfolio)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        $portfolio = $repository->update($portfolio, $request->all());

        return $this->success(
            new PortfolioResource($portfolio),
            PortfolioConstants::UPDATE,
            Response::HTTP_OK
        );
    }

    public function destroy(Portfolio $portfolio, PortfolioRepository $repository): JsonResponse
    {
        if (!$this->canAccess($portfolio)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        $repository->forceDelete($portfolio);

        return $this->success([], PortfolioConstants::DESTROY);
    }
}
