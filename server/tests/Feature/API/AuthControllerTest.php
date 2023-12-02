<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanRegisterAUser(): void
    {
        $this->postJson('/api/v1/register', [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'password',
        ])
            ->assertCreated()
            ->assertJson([
                'status' => true,
                'message' => 'Registered successfully',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@doe.com',
        ]);
    }

    public function testItCanAuthenticateAUser(): void
    {
        $user = User::factory()->create([
            'email' => 'john@doe.com',
            'password' => bcrypt('password'),
        ]);

        $this->postJson('/api/v1/login', [
            'email' => 'john@doe.com',
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'Authenticated',
            ]);

        $this->assertAuthenticatedAs($user);
    }

    public function testItCannotAuthenticateAUserWithInvalidCredentials(): void
    {
        $this->postJson('/api/v1/login', [
            'email' => 'john@doe.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function testItEmailsAreUnique(): void
    {
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => bcrypt('password'),
        ]);

        $this->postJson('/api/v1/register', [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'password',
        ])
            ->assertUnprocessable();
    }

    public function testItCanLogoutAUser(): void
    {
        $user = User::factory()->create([
            'email' => 'john@doe.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user)
            ->postJson('/api/v1/logout')
            ->assertNoContent();
    }

    public function testItCanGetTheCurrentlyAuthenticatedUser(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user)
            ->getJson('/api/v1/me')
            ->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    'name' => 'John Doe',
                    'email' => 'john@doe.com',
                ],
            ]);
    }
}
