<?php

namespace App\Repositories;

use App\Constants\TransactionConstants;
use App\Exceptions\GeneralJsonException;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends BaseRepository
{
    public function create(array $attributes, bool $updateAsset = true): mixed
    {
        return DB::transaction(function () use ($attributes, $updateAsset) {
            $type = data_get($attributes, 'type');
            $value = data_get($attributes, 'value');
            $assetId = data_get($attributes, 'asset_id');
            $asset = auth()->user()->assets()->find($assetId);
            $newAssetValue = $this->getTotalValue(
                $type,
                $asset->value,
                $value
            );

            $created = auth()->user()->transactions()->create([
                'asset_id' => $assetId,
                'description' => data_get($attributes, 'description'),
                'date' => data_get($attributes, 'date'),
                'type' => $type,
                'value' => $value,
                'asset_total_value' => $updateAsset ? $newAssetValue : $asset->value,
                'is_manual_movement' => data_get($attributes, 'is_manual_movement') ?? true
            ]);
            throw_if(!$created, GeneralJsonException::class, 'Failed to create new Transaction.');

            if ($updateAsset) {
                (new AssetRepository)->update($asset, [
                    'value' => $newAssetValue
                ]);
            }

            return $created;
        });
    }

    /**
     * @param Transaction $transaction
     */
    public function update($transaction, array $attributes): mixed
    {
        return DB::transaction(function () use ($transaction, $attributes) {
            $value = data_get($attributes, 'value');
            $newAssetValue = $transaction->asset_total_value;

            $updated = $transaction->update([
                'description' => data_get($attributes, 'description') ?? $transaction->description,
                'date' => data_get($attributes, 'date') ?? $transaction->date,
                'type' => data_get($attributes, 'type') ?? $transaction->type,
                'value' => $value ?? $transaction->value,
                'asset_total_value' => $newAssetValue,
            ]);

            throw_if(!$updated, GeneralJsonException::class, 'Failed to update Transaction');

            if ($value) {
                $assetId = data_get($attributes, 'asset_id');
                $asset = auth()->user()->assets()->find($assetId);
                $type = data_get($attributes, 'type') ?? $transaction->type;

                $oldAssetValue = $this->reversalTotalValue(
                    $transaction->type,
                    $asset->value,
                    $transaction->value
                );

                $newAssetValue = $this->getTotalValue(
                    $type,
                    $oldAssetValue,
                    $value
                );

                (new AssetRepository)->update($asset, [
                    'value' => $newAssetValue
                ]);
            }

            return $transaction;
        });
    }

    public function reversalTotalValue(string $type, float $assetValue, float $transactionValue): mixed
    {
        return $type === TransactionConstants::CREDIT
            ? $assetValue - $transactionValue
            : $assetValue + $transactionValue;
    }

    public function getTotalValue(string $type, float $assetValue, float $transactionValue)
    {
        return $type === TransactionConstants::DEBIT
            ? $assetValue - $transactionValue
            : $assetValue + $transactionValue;
    }

    /**
     * @param Transaction $transaction
     */
    public function delete($transaction, bool $cascade = false): mixed
    {
        return DB::transaction(function () use ($transaction, $cascade) {
            if (!$cascade) {
                $assetId = $transaction->asset_id;
                $asset = auth()->user()->assets()->find($assetId);
                $type = $transaction->type;
                $value = $transaction->value;
                $newAssetValue = $this->reversalTotalValue(
                    $type,
                    $asset->value,
                    $value
                );

                (new AssetRepository)->update($asset, [
                    'value' => $newAssetValue
                ]);
            }

            $deleted = $transaction->forceDelete();

            throw_if(!$deleted, GeneralJsonException::class, "cannot delete Transaction.");

            return $deleted;
        });
    }
}
