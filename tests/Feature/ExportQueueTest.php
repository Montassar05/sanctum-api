<?php

namespace Tests\Feature;

use App\Jobs\ExportPosts;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExportQueueTest extends TestCase
{
    use RefreshDatabase;

    public function test_l_export_part_en_queue(): void
    {
        Queue::fake();
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/exports')->assertAccepted();

        Queue::assertPushed(ExportPosts::class);
    }

    public function test_le_job_ecrit_le_csv(): void
    {
        Storage::fake();
        $post = Post::factory()->create(['title' => 'Hello']);

        (new ExportPosts($post->user))->handle();

        Storage::assertExists("exports/user-{$post->user->id}.csv");
        $this->assertStringContainsString(
            'Hello', Storage::get("exports/user-{$post->user->id}.csv")
        );
    }
}
