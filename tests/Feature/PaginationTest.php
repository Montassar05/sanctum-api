<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_liste_est_paginee(): void
    {
        Post::factory()->count(40)->create();

        $this->getJson('/api/posts')
            ->assertJsonCount(15, 'data')
            ->assertJsonPath('total', 40);

        $this->getJson('/api/posts?page=3')
            ->assertJsonCount(10, 'data');
    }
}
