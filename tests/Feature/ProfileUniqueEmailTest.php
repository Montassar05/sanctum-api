<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileUniqueEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_garder_son_propre_email(): void
    {
        $user = User::factory()->create(['email' => 'moi@test.fr']);
        Sanctum::actingAs($user);

        $this->putJson('/api/me', [
            'name' => 'Nouveau nom', 'email' => 'moi@test.fr',
        ])->assertOk()->assertJsonPath('data.name', 'Nouveau nom');
    }

    public function test_prendre_l_email_d_un_autre_est_refuse(): void
    {
        User::factory()->create(['email' => 'autre@test.fr']);
        Sanctum::actingAs(User::factory()->create());

        $this->putJson('/api/me', [
            'name' => 'x', 'email' => 'autre@test.fr',
        ])->assertUnprocessable()->assertJsonValidationErrors('email');
    }
}
