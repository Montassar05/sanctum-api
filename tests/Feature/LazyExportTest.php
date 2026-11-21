<?php

namespace Tests\Feature;

use App\Jobs\ExportPosts;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LazyExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_l_export_lit_par_paquets(): void
    {
        Storage::fake();
        $user = User::factory()->hasPosts(1200)->create();

        DB::enableQueryLog();
        (new ExportPosts($user))->handle();
        $selects = collect(DB::getQueryLog())
            ->filter(fn ($q) => str_contains($q['query'], 'from "posts"'));

        // 1200 posts / 500 = 3 paquets (+1 requête vide de fin).
        $this->assertGreaterThanOrEqual(3, $selects->count());

        $lines = explode("\n", Storage::get("exports/user-{$user->id}.csv"));
        $this->assertCount(1201, $lines); // en-tête + 1200 lignes
    }
}
