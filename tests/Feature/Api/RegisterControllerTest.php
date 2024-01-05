<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    use WithFaker;

    public function test_index(): void
    {
        $password = $this->faker()->password(8);

        $response = $this->postJson('api/register', [
            'name' => $this->faker->name(),
            'username' => $this->faker->userName(),
            'email' => $this->faker->email(),
            'password' => $password,
            'confirmPassword' => $password,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }
}
