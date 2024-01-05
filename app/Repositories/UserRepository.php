<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{

    public function create(array $attributes): mixed
    {
        return DB::transaction(function () use ($attributes) {

            $created = User::query()->create([
                'name'  => data_get($attributes, 'name'),
                'username' => data_get($attributes, 'username'),
                'email' => data_get($attributes, 'email'),
                'password' => Hash::make(data_get($attributes, 'password')),
            ]);
            throw_if(!$created, GeneralJsonException::class, 'Failed to create model.');

            return $created;
        });
    }

    /**
     * @param User $user
     */
    public function update($user, array $attributes): mixed
    {
        return DB::transaction(function () use ($user, $attributes) {
            $updated = $user->update([
                'name'  => data_get($attributes, 'name', $user->name),
                'email' => data_get($attributes, 'email', $user->email),
            ]);
            throw_if(!$updated, GeneralJsonException::class, 'Failed to update user.');

            return $user;

        });
    }

    /**
     * @param User $user
     */
    public function delete($user, bool $force = false): mixed
    {
        return DB::transaction(function () use ($user) {
            $deleted = $user->forceDelete();

            throw_if(!$deleted, GeneralJsonException::class, 'Cannot delete user.');

            return $deleted;
        });


    }
}
