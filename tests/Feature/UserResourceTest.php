<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_me_ne_renvoie_que_les_champs_choisis(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->getJson('/api/me')
            ->assertOk()
            ->assertExactJsonStructure(['data' => ['id', 'name', 'email']])
            ->assertJsonPath('data.email', $user->email);
    }
}
