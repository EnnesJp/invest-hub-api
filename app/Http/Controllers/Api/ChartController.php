<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssetChartResource;
use App\Http\Traits\Access;
use App\Http\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;

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
            ->selectRaw('asset_id, date as month, asset_total_value')
            ->where('date', '>=', $lastYear . '-' . $month . '-01')
            ->get();

        $chartData = [];
        foreach ($assets as $asset) {
            $asset->month = date('m/Y', strtotime($asset->month));
            $chartData[$asset->asset_id][] = $asset;
        }

        return $this->success(
            $chartData,
        );
    }
}
