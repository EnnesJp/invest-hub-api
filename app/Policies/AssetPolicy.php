<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

use Illuminate\Auth\Access\Response;

class AssetPolicy
{
    public function view(User $user, Asset $asset): Response
    {
        return $user->id === $asset->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function update(User $user, Asset $asset): Response
    {
        return $user->id === $asset->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user, Asset $asset): Response
    {
        return $user->id === $asset->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function forceDelete(User $user, Asset $asset): Response
    {
        return $user->id === $asset->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
