<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_refuse_sans_token(): void
    {
        $this->getJson('/api/user')
            ->assertUnauthorized();
    }

    public function test_login_renvoie_un_token(): void
    {
        $user = User::factory()->create(['password' => 'secret123']);

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secret123',
            'device' => 'iphone',
        ])->assertOk()->assertJsonStructure(['token']);
    }

    public function test_login_refuse_mauvais_mot_de_passe(): void
    {
        $user = User::factory()->create(['password' => 'secret123']);

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'mauvais',
            'device' => 'iphone',
        ])->assertUnauthorized();
    }

    public function test_token_donne_acces_a_user(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/user')
            ->assertOk()
            ->assertJson(['email' => $user->email]);
    }

    public function test_acces_avec_sanctum(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->getJson('/api/user')->assertOk();
    }

    public function test_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test');

        $this->withToken($token->plainTextToken)
            ->postJson('/api/logout')
            ->assertNoContent();

        $this->assertCount(0, $user->tokens);
    }
}
