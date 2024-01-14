<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\Asset;
use App\Models\Transaction;
use App\Constants\TransactionConstants;
use Illuminate\Support\Facades\DB;

class AssetRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $created = auth()->user()->assets()->create([
                'portfolio_id' => data_get($attributes, 'portfolio_id'),
                'saving_plan_id' => data_get($attributes, 'saving_plan_id'),
                'name' => data_get($attributes, 'name'),
                'value' => data_get($attributes, 'value'),
                'acquisition_date' => data_get($attributes, 'acquisition_date'),
                'quantity' => data_get($attributes, 'quantity') ?? null,
                'liquidity_days' => data_get($attributes, 'liquidity_days') ?? null,
                'liquidity_date' => data_get($attributes, 'liquidity_date') ?? null,
                'income_tax' => data_get($attributes, 'income_tax') ?? 0.00
            ]);
            throw_if(!$created, GeneralJsonException::class, 'Failed to create asset. ');

            (new TransactionRepository)->create([
                'asset_id' => $created->id,
                'description' => 'Creating Asset',
                'date' => now(),
                'type' => TransactionConstants::CREDIT,
                'value' => $created->value,
                'is_manual_movement' => false
            ], false);

            $portfolio = auth()->user()->portfolios()->find($created->portfolio_id);
            $newBalance = $portfolio->balance + $created->value;

            (new PortfolioRepository)->update($portfolio, [
                'balance' => $newBalance
            ]);

            return $created;
        });
    }

    /**
     * @param Asset $asset
     */
    public function update(
        $asset,
        array $attributes,
        bool $createTransaction = false
    ): mixed {
        return DB::transaction(function () use ($asset, $attributes, $createTransaction) {
            $value = data_get($attributes, 'value');
            $oldValue = $asset->value;

            if ($createTransaction && $value) {
                $transactionValue = $value - $oldValue;
                $transactionType = $transactionValue > 0
                    ? TransactionConstants::CREDIT
                    : TransactionConstants::DEBIT;

                (new TransactionRepository)->create([
                    'asset_id' => $asset->id,
                    'description' => 'Update Asset',
                    'date' => now(),
                    'type' => $transactionType,
                    'value' => $transactionValue,
                    'is_manual_movement' => false
                ]);
            }

            $updated = $asset->update([
                'portfolio_id' => data_get($attributes, 'portfolio_id') ?? $asset->portfolio_id,
                'saving_plan_id' => data_get($attributes, 'saving_plan_id') ?? $asset->saving_plan_id,
                'name' => data_get($attributes, 'name') ?? $asset->name,
                'value' => $value ?? $asset->value,
                'quantity' => data_get($attributes, 'quantity') ?? $asset->quantity,
                'liquidity_days' => data_get($attributes, 'liquidity_days') ?? $asset->liquidity_days,
                'liquidity_date' => data_get($attributes, 'liquidity_date') ?? $asset->liquidity_date,
                'income_tax' => data_get($attributes, 'income_tax') ?? $asset->income_tax,
            ]);

            throw_if(!$updated, GeneralJsonException::class, 'Failed to update asset');

            if ($value) {
                $portfolioId = $asset->portfolio_id;
                $portfolio = auth()->user()->portfolios()->find($portfolioId);
                $newBalance = $portfolio->balance + ($value - $oldValue);

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
    public function delete($asset, bool $cascade = false): mixed
    {
        return DB::transaction(function () use ($asset, $cascade) {
            if (!$cascade) {
                $portfolioId = $asset->portfolio_id;
                $portfolio = auth()->user()->portfolios()->find($portfolioId);
                $newBalance = $portfolio->balance - $asset->value;

                (new PortfolioRepository)->update($portfolio, [
                    'balance' => $newBalance
                ]);
            }

            $this->removeTransactions($asset, true);
            $deleted = $asset->forceDelete();

            throw_if(!$deleted, GeneralJsonException::class, "cannot delete asset.");

            return $deleted;
        });
    }

    public function removeTransactions(Asset $asset): void
    {
        $asset->transactions->each(function (Transaction $transaction) {
            (new TransactionRepository)->delete($transaction, true);
        });
    }
}
