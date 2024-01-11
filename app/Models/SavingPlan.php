<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SavingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'target_value',
        'target_date',
        'acquisition_date',
    ];

    protected $casts = [
        'target_date' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'saving_plan_id', 'id');
    }

    public function scopeForUser(Builder $query): Builder
    {
        return $query->where('user_id', auth()->id());
    }
}
