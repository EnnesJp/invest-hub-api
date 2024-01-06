<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'asset_id',
        'description',
        'date',
        'type',
        'value',
        'asset_total_value',
        'is_manual_movement'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function scopeForUser(Builder $query): Builder
    {
        return $query->where('user_id', auth()->id());
    }

    public function scopeForAsset(Builder $query, $assetId): Builder
    {
        return $query->where('asset_id', $assetId);
    }

    public function scopeForPortfolio(Builder $query, $portfolioId): Builder
    {
        return $query->whereHas('asset', function ($query) use ($portfolioId) {
            $query->where('portfolio_id', $portfolioId);
        });
    }
}
