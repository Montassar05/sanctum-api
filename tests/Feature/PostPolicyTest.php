<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_autre_user_ne_peut_pas_modifier(): void
    {
        $post = Post::factory()->create();
        Sanctum::actingAs(User::factory()->create());

        $this->putJson("/api/posts/{$post->id}", ['title' => 'Hack', 'body' => 'x'])
            ->assertForbidden();
    }

    public function test_l_auteur_peut_modifier(): void
    {
        $post = Post::factory()->create();
        Sanctum::actingAs($post->user);

        $this->putJson("/api/posts/{$post->id}", ['title' => 'Nouveau', 'body' => 'x'])
            ->assertOk()
            ->assertJsonPath('title', 'Nouveau');
    }
}
