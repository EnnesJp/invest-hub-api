<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;

trait Access
{
    protected function canAccess(Model $model): bool
    {
        return !($model->user_id !== auth()->id());
    }
}
