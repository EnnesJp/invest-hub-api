<?php

namespace App\Policies;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PortfolioPolicy
{
    public function view(User $user, Portfolio $portfolio): Response
    {
        return $user->id === $portfolio->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function update(User $user, Portfolio $portfolio): Response
    {
        return $user->id === $portfolio->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user, Portfolio $portfolio): Response
    {
        return $user->id === $portfolio->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function forceDelete(User $user, Portfolio $portfolio): Response
    {
        return $user->id === $portfolio->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
