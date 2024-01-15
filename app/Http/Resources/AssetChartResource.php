<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetChartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        $date = date_create($this->month);

        return [
            'month' => date_format($date, "m/Y"),
            'total' => floatval($this->total),
        ];
    }
}
