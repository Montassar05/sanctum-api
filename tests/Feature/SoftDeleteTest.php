<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SoftDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_garde_le_post_en_base(): void
    {
        $post = Post::factory()->create();
        Sanctum::actingAs($post->user);

        $this->deleteJson("/api/posts/{$post->id}")->assertNoContent();

        $this->assertSoftDeleted($post);
        $this->assertNull(Post::find($post->id));
    }

    public function test_restore_le_fait_revenir(): void
    {
        $post = Post::factory()->create();
        $post->delete();

        Post::withTrashed()->find($post->id)->restore();

        $this->assertNotNull(Post::find($post->id));
    }
}
