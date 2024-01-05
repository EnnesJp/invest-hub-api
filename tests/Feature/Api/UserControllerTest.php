<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->make();
        $this->actingAs($user);
    }

    public function test_index(): void
    {
        $response = $this->getJson('api/user');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_show(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson('api/user/' . $user->id);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_destroy(): void
    {
        $user = User::factory()->create();

        $response = $this->deleteJson('api/user/' . $user->id);

        $response->assertStatus(Response::HTTP_OK);
    }
}
