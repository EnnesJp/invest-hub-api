<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;

class AssetRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {

            $created = auth()->user()->assets()->create([
                'portfolio_id' => data_get($attributes, 'portfolio_id'),
                'name' => data_get($attributes, 'name'),
                'value' => data_get($attributes, 'value'),
                'acquisition_date' => data_get($attributes, 'acquisition_date'),
            ]);
            throw_if(!$created, GeneralJsonException::class, 'Failed to create asset. ');

            (new PortfolioRepository)->updateBalance($created->portfolio_id, $created->value);

            return $created;
        });
    }

    /**
     * @param Asset $asset
     */
    public function update($asset, array $attributes): mixed
    {
        return DB::transaction(function () use($asset, $attributes) {
            $value = data_get($attributes, 'value');
            $oldValue = $asset->value;
            $updated = $asset->update([
                'name' => data_get($attributes, 'name') ?? $asset->name,
                'value' => $value ?? $asset->value,
            ]);

            throw_if(!$updated, GeneralJsonException::class, 'Failed to update asset');

            if ($value) {
                $portfolioId = $asset->portfolio_id;
                $portfolio = auth()->user()->portfolios()->find($portfolioId);
                $newBalance = $portfolio->balance - $oldValue + $value;

                (new PortfolioRepository)->update($portfolio, [
                    'balance' => $newBalance
                ]);
            }

            return $asset;
        });
    }

    /**
     * @param Asset $asset
     */
    public function forceDelete($asset): mixed
    {
        return DB::transaction(function () use($asset) {
            $portfolioId = $asset->portfolio_id;
            $portfolio = auth()->user()->portfolios()->find($portfolioId);
            $newBalance = $portfolio->balance - $asset->value;

            (new PortfolioRepository)->update($portfolio, [
                'balance' => $newBalance
            ]);

            $deleted = $asset->forceDelete();

            throw_if(!$deleted, GeneralJsonException::class, "cannot delete asset.");

            return $deleted;
        });
    }
}
