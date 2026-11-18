<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishedScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_seuls_les_posts_publies_sortent(): void
    {
        Post::factory()->count(2)->create();
        Post::factory()->draft()->create();
        Post::factory()->create(['published_at' => now()->addWeek()]);

        $this->assertCount(2, Post::published()->get());

        $this->getJson('/api/posts')->assertJsonCount(2);
    }
}
