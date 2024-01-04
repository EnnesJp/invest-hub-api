<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {

            $created = auth()->user()->transactions()->create([
                'asset_id' => data_get($attributes, 'asset_id'),
                'date' => data_get($attributes, 'date'),
                'type' => data_get($attributes, 'type'),
                'value' => data_get($attributes, 'value'),
                'asset_total_value' => data_get($attributes, 'asset_total_value'),
            ]);
            throw_if(!$created, GeneralJsonException::class, 'Failed to create. ');

            return $created;
        });
    }

    /**
     * @param Transaction $transaction
     */
    public function update($transaction, array $attributes): mixed
    {
        return DB::transaction(function () use($transaction, $attributes) {
            $updated = $transaction->update([
                'date' => data_get($attributes, 'date'),
                'type' => data_get($attributes, 'type'),
                'value' => data_get($attributes, 'value'),
                'asset_total_value' => data_get($attributes, 'asset_total_value'),
            ]);

            throw_if(!$updated, GeneralJsonException::class, 'Failed to update expense');

            return $transaction;
        });
    }

    /**
     * @param Transaction $transaction
     */
    public function forceDelete($transaction): mixed
    {
        return DB::transaction(function () use($transaction) {
            $deleted = $transaction->forceDelete();

            throw_if(!$deleted, GeneralJsonException::class, "cannot delete expense.");

            return $deleted;
        });
    }
}
