<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StatsCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_deuxieme_appel_sans_requete_sql(): void
    {
        User::factory(2)->create();

        $this->getJson('/api/stats')->assertJson(['users' => 2]);

        DB::enableQueryLog();
        $this->getJson('/api/stats')->assertJson(['users' => 2]);
        $this->assertCount(0, DB::getQueryLog());
    }
}
