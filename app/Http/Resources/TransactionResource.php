<?php

namespace App\Http\Resources;

use App\Models\Asset;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
    */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'asset_id' => $this->asset_id,
            'date' => $this->date,
            'type' => $this->type,
            'value' => $this->value,
            'asset_total_value' => $this->asset_total_value,
        ];
    }
}
