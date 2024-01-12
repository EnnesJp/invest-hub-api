<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SavingPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
    */
    public function toArray($request): array
    {
        $target_date = date_create($this->target_date);

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'description' => $this->description,
            'target_value' => floatval($this->target_value),
            'target_date' => date_format($target_date, "d/m/Y"),
            'total_accumulated' => floatval($this->total_accumulated),
        ];
    }
}
