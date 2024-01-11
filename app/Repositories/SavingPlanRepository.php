<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\Asset;
use App\Models\SavingPlan;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class SavingPlanRepository extends BaseRepository
{
    public function create(array $attributes): SavingPlan
    {
        return DB::transaction(function () use ($attributes) {

            $created = auth()->user()->savingPlans()->create([
                'name' => data_get($attributes, 'name'),
                'description' => data_get($attributes, 'description'),
                'target_value' => data_get($attributes, 'target_value'),
                'target_date' => data_get($attributes, 'target_date'),
            ]);
            throw_if(!$created, GeneralJsonException::class, 'Failed to create saving plan. ');

            return $created;
        });
    }

    /**
     * @param SavingPlan $savingPlan
     */
    public function update($savingPlan, array $attributes): SavingPlan
    {
        return DB::transaction(function () use($savingPlan, $attributes) {
            $updated = $savingPlan->update([
                'name' => data_get($attributes, 'name') ?? $savingPlan->name,
                'description' => data_get($attributes, 'description') ?? $savingPlan->description,
                'target_value' => data_get($attributes, 'target_value') ?? $savingPlan->target_value,
                'target_date' => data_get($attributes, 'target_date') ?? $savingPlan->target_date,
            ]);

            throw_if(!$updated, GeneralJsonException::class, 'Failed to update saving plan');

            return $savingPlan;
        });
    }

    /**
     * @param SavingPlan $savingPlan
     */
    public function delete($savingPlan, bool $cascade = false): mixed
    {
        return DB::transaction(function () use($savingPlan) {
            $this->updateAssets($savingPlan);
            $deleted = $savingPlan->forceDelete();

            throw_if(!$deleted, GeneralJsonException::class, "cannot delete saving plan.");

            return $deleted;
        });
    }

    public function updateAssets(SavingPlan $savingPlan): void
    {
        $savingPlan->assets->each(function (Asset $asset){
            (new AssetRepository)->update($asset, [
                'saving_plan_id' => null
            ]);
        });
    }
}
