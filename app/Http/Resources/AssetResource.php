<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
    */
    public function toArray($request): array
    {
        $acquisition_date = date_create($this->acquisition_date);

        return [
            'id' => $this->id,
            'portfolio_id' => $this->portfolio_id,
            'name' => $this->name,
            'value' => floatval($this->value),
            'acquisition_date' => date_format($acquisition_date, "d/m/Y")
        ];
    }
}
