<?php

namespace Tests\Feature\Functional;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'password' => bcrypt('Test1234!')
        ]);
    }

    public function test_user_can_login_successfully(): void
    {
        $this->postJson('/api/auth/login', [
            'email'    => $this->user->email,
            'password' => 'Test1234!',
        ])
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'access_token',
                'refresh_token',
            ]
        ]);
    }

    public function test_invalid_login(): void
    {
        $this->postJson('/api/auth/login', [
            'email'    => 'hello@2.com',
            'password' => 'xxx',
        ])
        ->assertStatus(401)
        ->assertJson([
        'status'  => 401,
        'error'   => 'Unauthorized',
        'message' => 'Invalid credentials provided. Please check your email and password'
        ]);
    }

    public function test_register_user_successfully(): void
    {
        $this->postJson('/api/auth/register', [
            'name' => 'Jhon Doe',
            'email' => 'jhondoe@test.com',
            'password' => 'JhonDoe123.'
        ])->assertOk()->assertJsonStructure([
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'image_url'
                ],
                'access_token',
                'refresh_token',
                'token_type',
                'expires_in',
                'refresh_expires_in'
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Jhon Doe',
            'email' => 'jhondoe@test.com',
        ]);
    }

    public function test_register_assigns_default_role_to_new_user(): void
    {
        $this->postJson('/api/auth/register', [
            'name' => 'Jhon Doe 2',
            'email' => 'jhondoe2@test.com',
            'password' => 'JhonDoe123.',
            'role' => 'admin'
        ])->assertOk()->assertJsonStructure([
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'image_url'
                ],
                'access_token',
                'refresh_token',
                'token_type',
                'expires_in',
                'refresh_expires_in'
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Jhon Doe 2',
            'email' => 'jhondoe2@test.com',
            'role' => 'client'
        ]);
    }

    public function test_register_fails_with_email_already_exists(): void
    {
        $this->postJson('/api/auth/register', [
            'name' => 'User 84',
            'email' => $this->user->email,
            'password' => 'JhonDoe123.'
        ])->assertStatus(422)->assertJsonValidationErrors(['email']);

        $this->assertDatabaseCount('users', 1);

        $this->assertDatabaseMissing('users', [
            'name' => 'User 84',
        ]);
    }

    public function test_register_fails_without_required_fields(): void
    {
        $this->postJson('/api/auth/register', [
        ])->assertStatus(422)->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_register_fails_with_invalid_email_format(): void
    {
        $this->postJson('/api/auth/register', [
            'name' => 'User 85',
            'email' => '112m@.m',
            'password' => 'JhonDoe123.'
        ])->assertStatus(422)->assertJsonValidationErrors(['email']);

        $this->assertDatabaseMissing('users', [
            'name' => 'User 85',
        ]);
    }

    public function test_register_fails_when_password_is_too_short(): void
    {
        $this->postJson('/api/auth/register', [
            'name' => 'User 86',
            'email' => '112m@.m',
            'password' => 'kk.'
        ])->assertStatus(422)->assertJsonValidationErrors(['password']);

        $this->assertDatabaseMissing('users', [
            'name' => 'User 86',
        ]);
    }

    public function test_register_password_is_hashed_in_database(): void
    {
        $user = $this->postJson('/api/auth/register', [
            'name' => 'Jhon Doe 3',
            'email' => 'jhondoe3@test.com',
            'password' => 'JhonDoe123.',
        ])->assertOk();

        $user = User::where('email', 'jhondoe3@test.com')->first();

        $this->assertNotNull($user);

        $this->assertNotEquals('JhonDoe123.', $user->password);

        $this->assertTrue(Hash::check('JhonDoe123.', $user->password));
    }

    public function test_register_token_has_correct_expiration(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'     => 'Jhon Doe 4',
            'email'    => 'jhondoe4@test.com',
            'password' => 'JhonDoe123.'
        ])
        ->assertStatus(200);

        $this->assertEquals(3600, $response->json('data.expires_in'));
    }

}
