<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssetChartResource;
use App\Http\Traits\Access;
use App\Http\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ChartController extends Controller
{
    use Access;
    use HttpResponses;

    public function getTotalAssetsGroupedByType(): JsonResponse
    {
        $lastYear = now()->year - 1;
        $month = now()->month;
        $assets = auth()
            ->user()
            ->transactions()
            ->selectRaw('date as month, sum(asset_total_value) as total')
            ->where('date', '>=', $lastYear . '-' . $month . '-01')
            ->groupBy('month')
            ->get();

        return $this->success(
            AssetChartResource::collection($assets),
            null,
            Response::HTTP_OK
        );
    }
}
