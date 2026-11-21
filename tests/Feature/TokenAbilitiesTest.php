<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TokenAbilitiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_token_lecture_seule_ne_peut_pas_publier(): void
    {
        $post = Post::factory()->draft()->create();
        Sanctum::actingAs($post->user, ['posts:read']);

        $this->postJson("/api/posts/{$post->id}/publish")
            ->assertForbidden();
    }

    public function test_un_token_avec_la_permission_publie(): void
    {
        $post = Post::factory()->draft()->create();
        Sanctum::actingAs($post->user, ['posts:publish']);

        $this->postJson("/api/posts/{$post->id}/publish")
            ->assertOk();

        $this->assertNotNull($post->fresh()->published_at);
    }

    public function test_un_vrai_token_limite(): void
    {
        $post = Post::factory()->draft()->create();
        $token = $post->user
            ->createToken('mobile', ['posts:read'])
            ->plainTextToken;

        $this->withToken($token)
            ->postJson("/api/posts/{$post->id}/publish")
            ->assertForbidden();
    }
}
