<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RouteModelBindingTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_post_inexistant_renvoie_404(): void
    {
        Sanctum::actingAs(\App\Models\User::factory()->create());

        $this->putJson('/api/posts/999', [
            'title' => 'x', 'body' => 'x',
        ])->assertNotFound();
    }

    public function test_un_post_supprime_renvoie_404(): void
    {
        $post = Post::factory()->create();
        $post->delete();
        Sanctum::actingAs($post->user);

        $this->deleteJson("/api/posts/{$post->id}")
            ->assertNotFound();
    }
}
