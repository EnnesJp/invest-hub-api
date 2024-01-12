<?php

namespace App\Policies;

use App\Models\SavingPlan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SavingPlanPolicy
{
    public function view(User $user, SavingPlan $savingPlan): Response
    {
        return $user->id === $savingPlan->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function update(User $user, SavingPlan $savingPlan): Response
    {
        return $user->id === $savingPlan->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user, SavingPlan $savingPlan): Response
    {
        return $user->id === $savingPlan->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function forceDelete(User $user, SavingPlan $savingPlan): Response
    {
        return $user->id === $savingPlan->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
