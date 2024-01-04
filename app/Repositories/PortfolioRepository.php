<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\Portfolio;
use Illuminate\Support\Facades\DB;

class PortfolioRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {

            $created = auth()->user()->portfolios()->create([
                'name' => data_get($attributes, 'name'),
                'description' => data_get($attributes, 'description'),
                'balance' => data_get($attributes, 'balance'),
            ]);
            throw_if(!$created, GeneralJsonException::class, 'Failed to create. ');

            return $created;
        });
    }

    /**
     * @param Portfolio $portfolio
     */
    public function update($portfolio, array $attributes): mixed
    {
        return DB::transaction(function () use($portfolio, $attributes) {
            $updated = $portfolio->update([
                'name' => data_get($attributes, 'name'),
                'description' => data_get($attributes, 'description'),
                'balance' => data_get($attributes, 'balance'),
            ]);

            throw_if(!$updated, GeneralJsonException::class, 'Failed to update expense');

            return $portfolio;
        });
    }

    /**
     * @param Portfolio $portfolio
     */
    public function forceDelete($portfolio): mixed
    {
        return DB::transaction(function () use($portfolio) {
            $deleted = $portfolio->forceDelete();

            throw_if(!$deleted, GeneralJsonException::class, "cannot delete expense.");

            return $deleted;
        });
    }
}
