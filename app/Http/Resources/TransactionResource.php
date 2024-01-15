<?php

namespace App\Http\Resources;

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
        $date = date_create($this->date);

        return [
            'id' => $this->id,
            'asset_id' => $this->asset_id,
            'description' => $this->description,
            'date' => date_format($date, "d/m/Y"),
            'type' => $this->type,
            'value' => floatval($this->value),
            'asset_total_value' => floatval($this->asset_total_value),
            'is_manual_movement' => $this->is_manual_movement,
        ];
    }
}
