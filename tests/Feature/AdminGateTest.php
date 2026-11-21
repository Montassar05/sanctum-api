<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminGateTest extends TestCase
{
    use RefreshDatabase;

    public function test_l_admin_passe_toutes_les_policies(): void
    {
        $post = Post::factory()->create();
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        Sanctum::actingAs($admin);

        $this->putJson("/api/posts/{$post->id}", [
            'title' => 'Modéré', 'body' => 'x',
        ])->assertOk();
    }

    public function test_un_user_normal_reste_bloque(): void
    {
        $post = Post::factory()->create();
        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson("/api/posts/{$post->id}")
            ->assertForbidden();
    }
}
