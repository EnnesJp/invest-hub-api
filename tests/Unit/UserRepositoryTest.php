<?php

namespace Tests\Unit;

use App\Exceptions\GeneralJsonException;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_create()
    {
        $repository = $this->app->make(UserRepository::class);

        $payload = [
            'name' => 'heyaa',
            'email' => 'abc@example.com',
            'password' => 'secret',
        ];

        $result = $repository->create($payload);

        $this->assertSame($payload['name'], $result->name, 'User created does not have the same name.');
    }

    public function test_update()
    {
        $repository = $this->app->make(UserRepository::class);
        $dummyUser = User::factory(1)->create()->first();

        $payload = [
            'name' => 'abc123',
        ];

        $updated = $repository->update($dummyUser, $payload);
        $this->assertSame($payload['name'], $updated->name, 'User updated does not have the same name.');
    }

    public function test_delete_will_throw_exception_when_delete_user_that_doesnt_exist()
    {
        $repository = $this->app->make(UserRepository::class);
        $dummy = User::factory(1)->make()->first();

        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->delete($dummy);
    }

    public function test_delete()
    {
        $repository = $this->app->make(UserRepository::class);
        $dummy = User::factory(1)->create()->first();

        $deleted = $repository->delete($dummy);
        $found = User::query()->find($dummy->id);

        $this->assertSame(null, $found, 'User is not deleted');

    }
}
