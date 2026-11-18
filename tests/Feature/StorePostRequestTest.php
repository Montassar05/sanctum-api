<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StorePostRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_champs_manquants_renvoient_422(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/posts', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'body']);
    }

    public function test_post_valide_est_cree(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/posts', ['title' => 'Hello', 'body' => 'Mon article'])
            ->assertCreated()
            ->assertJsonPath('title', 'Hello');
    }
}
