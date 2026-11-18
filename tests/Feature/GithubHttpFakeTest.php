<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GithubHttpFakeTest extends TestCase
{
    public function test_aucun_vrai_appel_reseau(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'api.github.com/*' => Http::response(['public_repos' => 42]),
        ]);

        $this->getJson('/api/github/taylorotwell')
            ->assertOk()
            ->assertJson(['repos' => 42]);

        Http::assertSent(fn (Request $r) =>
            str_contains($r->url(), 'taylorotwell'));
    }

    public function test_erreur_github_renvoie_500(): void
    {
        Http::fake(['api.github.com/*' => Http::response([], 503)]);

        $this->getJson('/api/github/x')->assertServerError();
    }
}
